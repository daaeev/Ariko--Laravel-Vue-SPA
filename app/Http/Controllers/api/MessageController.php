<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMessage;
use App\Models\Message;

class MessageController extends Controller
{
    /**
     * Создание сообщения
     *
     * @param CreateMessage $validate
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateMessage $validate)
    {
        $model = Message::create($validate->validated());

        return response()->json($model);
    }
}
