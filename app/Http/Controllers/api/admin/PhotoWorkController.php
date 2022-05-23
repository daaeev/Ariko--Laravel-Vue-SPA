<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddImagesToWork;
use App\Http\Requests\CreatePhotoWork;
use App\Models\Image;
use App\Models\PhotoWork;
use App\Services\ImageProcessing\Interfaces\ImageProcessingInterface;
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
        $model->setRawAttributes($data);

        if (!$model->save()) {
            throw new HttpException(500, 'Save data in db failed');
        }

        return response()->json($model);
    }

    /**
     * Добавление изображений для работы
     *
     * @param ImageProcessingInterface $imgProcess
     * @param AddImagesToWork $validate
     * @return \Illuminate\Http\JsonResponse
     */
    public function addImagesToWork(
        ImageProcessingInterface $imgProcess,
        AddImagesToWork $validate
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
                $imgProcess->deleteImage($savedName);
            }

            throw new HttpException(500, 'Save images in db failed');
        }

        return response()->json($imagesModels);
    }

    /**
     * Сохранение изображений в локальном хранилище
     *
     * @param array[UploadedImage] $images
     * @param ImageProcessingInterface $imgProcess
     * @return array[String]|boolean
     */
    protected function saveImagesInLocalStorage(
        array $images, 
        ImageProcessingInterface $imgProcess
    ): array|bool {
        $savedImages = [];

        foreach ($images as $image) {
            $name = $imgProcess->saveImage($image);

            if (!$name) {
                foreach ($savedImages as $savedName) {
                    $imgProcess->deleteImage($savedName);
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
     * @param array[String] $images
     * @param integer $work_id
     * @return array[Image]|boolean
     */
    protected function saveImagesInDb(array $images, int $work_id)
    {
        $created = [];
        
        foreach ($images as $image_name) {
            $model = Image::create([
                'image' => $image_name,
                'photo_work_id' => $work_id
            ]);

            $created[] = $model;
        }

        return $created;
    }
}
