<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateVideoWork;
use App\Models\VideoWork;
use App\Services\FileProcessing\FileNameGenerators\Interfaces\FileNameGeneratorInterface;
use App\Services\FileProcessing\Interfaces\FileProcessingInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class VideoWorkController extends Controller
{
    /**
     * Создание работы (видео)
     *
     * @param VideoWork $model
     * @param FileProcessingInterface $fileProcessing
     * @param CreateVideoWork $validate
     * @return \Illuminate\Http\JsonResponse
     */
    public function createWork(
        VideoWork $model,
        FileProcessingInterface $fileProcessing,
        CreateVideoWork $validate
    ) {
        $data = $validate->validated();

        $video = $data['video'];

        $fileProcessing->disk('public')->directory('videos');

        $fnGen = app(FileNameGeneratorInterface::class);

        $video_name = $fileProcessing->saveFile($video, $fnGen);

        if (!$video_name) {
            throw new HttpException(500, 'Save video in LS failed');
        }

        $data['video'] = $video_name;

        $model->fill($data);

        if (!$model->save()) {
            $fileProcessing->deleteFile($video_name);

            throw new HttpException(500, 'Model save failed');
        }

        return response()->json($model);
    }
}
