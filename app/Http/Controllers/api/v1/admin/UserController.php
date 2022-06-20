<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserController extends Controller
{
    /**
     * Создание пользователя
     *
     * @param User $model
     * @param CreateUser $validate
     * @return \Illuminate\Http\JsonResponse
     * @throws HttpException
     */
    public function create(User $model, CreateUser $validate): \Illuminate\Http\JsonResponse
    {
        $data = $validate->validated();
        $data['password'] = Hash::make($data['password']);

        $model->fill($data);

        if ($model->save()) {
            return response()->json($model);
        }

        throw new HttpException(500, 'Save in db failed');
    }

    /**
     * Удаление пользователя
     *
     * @param User $model
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser(User $model): \Illuminate\Http\JsonResponse
    {
        $model_id = $model->id;

        if (!$model->delete()) {
            throw new HttpException(500, 'Model delete failed');
        }

        return response()->json(['id' => $model_id]);
    }
}
