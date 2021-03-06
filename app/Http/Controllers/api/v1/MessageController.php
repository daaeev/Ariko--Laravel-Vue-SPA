<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMessage;
use App\Models\Message;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MessageController extends Controller
{
    /**
     * Создание сообщения
     *
     * @param CreateMessage $validate
     * @return \Illuminate\Http\JsonResponse
     * @throws HttpException
     */
    public function create(Message $model, CreateMessage $validate): \Illuminate\Http\JsonResponse
    {
        $model->fill($validate->validated());

        if (!$model->save()) {
            throw new HttpException(500, 'Save in database failed');
        }

        return response()->json($model);
    }
}
