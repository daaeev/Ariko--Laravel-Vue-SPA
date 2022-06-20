<?php

namespace App\Http\Controllers\api\v1\admin;

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
    ): \Illuminate\Http\JsonResponse {
        $data = $validate->validated();
        $fileProc->disk('public')->directory('posts_previews');

        $main_image = $data['main_image'];
        $preview_image = $data['preview_image'] ?? false;

        $fnGen = app(FileNameGeneratorInterface::class);

        // Сохранение изображений в локальном хранилищи
        $main_name = $fileProc->saveFile($main_image, $fnGen);

        if (!$main_name) {
            throw new HttpException(500, 'Main image save in LS failed');
        }

        if ($preview_image) {
            $preview_name = $fileProc->saveFile($preview_image, $fnGen);

            if (!$preview_name) {
                $fileProc->deleteFile($main_name);

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
            $fileProc->deleteFile($main_name);

            if ($preview_image) {
                $fileProc->deleteFile($preview_name);
            }

            throw new HttpException(500, 'Model save in db failed');
        }

        return response()->json($model);
    }

    /**
     * Удаление поста
     *
     * @param Post $model
     * @param FileProcessingInterface $fileProc
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePost(Post $model, FileProcessingInterface $fileProc): \Illuminate\Http\JsonResponse
    {
        $model_id = $model->id;
        $main_image = $model->main_image;
        $preview_image = $model->preview_image;

        $fileProc->disk('public')->directory('posts_previews');

        if (!$fileProc->deleteFile($main_image)) {
            throw new HttpException(500, 'Main image delete failed');
        }

        if (!empty($preview_image) && !$fileProc->deleteFile($preview_image)) {
            throw new HttpException(500, 'Preview image delete failed');
        }

        if (!$model->delete()) {
            throw new HttpException(500, 'Previews deleted, but model delete failed');
        }

        return response()->json(['id' => $model_id]);
    }
}
