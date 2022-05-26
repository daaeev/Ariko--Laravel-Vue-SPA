<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddImagesToWork;
use App\Http\Requests\CreatePhotoWork;
use App\Models\Image;
use App\Models\PhotoWork;
use App\Services\FileProcessing\FileNameGenerators\Interfaces\FileNameGeneratorInterface;
use App\Services\FileProcessing\Interfaces\FileProcessingInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PhotoWorkController extends Controller
{
    /**
     * Создание работы
     *
     * @param PhotoWork $model
     * @param CreatePhotoWork $validate
     * @return \Illuminate\Http\JsonResponse
     */
    public function createWork(
        PhotoWork $model,
        CreatePhotoWork $validate
    ) {
        $data = $validate->validated();

        // Занесение данных работы в бд
        $model->fill($data);

        if (!$model->save()) {
            throw new HttpException(500, 'Save data in db failed');
        }

        return response()->json($model);
    }

    /**
     * Добавление изображений для работы
     *
     * @param FileProcessingInterface $imgProcess
     * @param AddImagesToWork $validate
     * @return \Illuminate\Http\JsonResponse
     */
    public function addImagesToWork(
        FileProcessingInterface $imgProcess,
        AddImagesToWork         $validate
    ) {
        $data = $validate->validated();
        $imgProcess->disk('public')->directory('photos');

        $lsImages = $this->saveImagesInLocalStorage($data['images'], $imgProcess);

        if (!$lsImages) {
            throw new HttpException(500, 'Save images in local storage failed');
        }

        $imagesModels = $this->saveImagesInDb($lsImages, $data['work_id']);

        if (!$imagesModels) {
            foreach ($lsImages as $savedName) {
                $imgProcess->deleteFile($savedName);
            }

            throw new HttpException(500, 'Save images in db failed');
        }

        return response()->json($imagesModels);
    }

    /**
     * Сохранение изображений в локальном хранилище
     *
     * @param array $images
     * @param FileProcessingInterface $imgProcess
     * @return array|bool
     */
    protected function saveImagesInLocalStorage(
        array $images,
        FileProcessingInterface $imgProcess
    ): array|bool {
        $savedImages = [];
        $fnGenService = app(FileNameGeneratorInterface::class);

        foreach ($images as $image) {
            $name = $imgProcess->saveFile($image, $fnGenService);

            // Если сохранение изображения прошло неуспешно
            if (!$name) {

                // Удалить уже сохраненные изображения
                foreach ($savedImages as $savedName) {
                    $imgProcess->deleteFile($savedName);
                }

                return false;
            }

            $savedImages[] = $name;
        }

        return $savedImages;
    }

    /**
     * Сохранение изображений в базе данных
     *
     * @param array $images
     * @param integer $work_id
     * @return array|bool
     */
    protected function saveImagesInDb(array $images, int $work_id): array|bool
    {
        $created = [];

        foreach ($images as $image_name) {
            $model = app(Image::class)->fill([
                'image' => $image_name,
                'photo_work_id' => $work_id
            ]);

            // Если сохранение модели в бд прошло неуспешно
            if (!$model->save()) {

                // Удалить уже созданные модели
                foreach ($created as $createdModel) {
                    $createdModel->delete();
                }

                return false;
            }

            $created[] = $model;
        }

        return $created;
    }
}
