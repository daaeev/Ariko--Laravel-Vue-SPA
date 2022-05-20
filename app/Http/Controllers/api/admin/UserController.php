<?php

namespace App\Http\Controllers\api\admin;

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
    public function create(User $model, CreateUser $validate)
    {
        $data = $validate->validated();
        $data['password'] = Hash::make($data['password']);

        $model->setRawAttributes($data);

        if ($model->save()) {
            return response()->json($model);
        }

        throw new HttpException(500, 'Save in db failed');
    }
}
