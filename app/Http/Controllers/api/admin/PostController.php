<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePost;
use App\Models\Post;
use App\Services\FileProcessing\FileNameGenerators\Interfaces\FileNameGeneratorInterface;
use App\Services\FileProcessing\Interfaces\FileProcessingInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PostController extends Controller
{
    /**
     * Создание поста
     *
     * @param FileProcessingInterface $fileProc
     * @param Post $model
     * @param CreatePost $validate
     * @return \Illuminate\Http\JsonResponse
     */
    public function createPost(
        FileProcessingInterface $fileProc,
        Post $model,
        CreatePost $validate
    ) {
        $data = $validate->validated();
        $fileProc->disk('public')->directory('posts_previews');

        $main_image = $data['main_image'];
        $preview_image = $validate->validated('preview_image');

        $fnGen = app(FileNameGeneratorInterface::class);

        // Сохранение изображений в локальном хранилищи
        $main_name = $fileProc->saveImage($main_image, $fnGen);

        if (!$main_name) {
            throw new HttpException(500, 'Main image save in LS failed');
        }

        if ($preview_image) {
            $preview_name = $fileProc->saveImage($preview_image, $fnGen);

            if (!$preview_name) {
                throw new HttpException(500, 'Preview image save in LS failed');
            }
        }

        // Занесение имен сохраненных изображенный в $data
        $data['main_image'] = $main_name;

        if ($preview_image) {
            $data['preview_image'] = $preview_name;
        }

        // Сохранение модели
        $model->fill($data);

        // Если сохранение неуспешно - удалить сохраненные изображения 
        if (!$model->save()) {
            $fileProc->deleteImage($main_name);

            if ($preview_image) {
                $fileProc->deleteImage($preview_name);
            }

            throw new HttpException(500, 'Model save in db failed');
        }

        return response()->json($model);
    }
}
