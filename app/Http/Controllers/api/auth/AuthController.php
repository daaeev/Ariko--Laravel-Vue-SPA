<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthToken;
use App\Http\Requests\UserLogin;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class AuthController extends Controller
{
    /**
     * Аутентификация пользователя
     *
     * @param UserLogin $validate
     * @return \Illuminate\Http\Response
     * @throws HttpException
     */
    public function login(UserLogin $validate)
    {
        $email = $validate->validated('email');
        $password = $validate->validated('password');
        $userDB_password = $this->query_helper
            ->queryBuilder(User::class)
            ->select('password')
            ->where('email', $email)
            ->first()
            ->password;

        if (!$userDB_password) {
            throw new HttpException(401, 'User does not exist');
        }

        if (!Hash::check($password, $userDB_password)) {
            throw new HttpException(401, 'User does not exist');
        }

        $encrypted_data = Crypt::encrypt(['email' => $email, 'password' => $password]);

        return response($encrypted_data);
    }

    /**
     * Проверка токена аутентификации
     *
     * @param AuthCheck $validate
     * @return \Illuminate\Http\Response
     * @throws HttpException
     */
    public function authCheck(AuthToken $validate)
    {
        $token = $validate->validated('token');

        try {
            $token_data = Crypt::decrypt($token); // ['email' => email, 'password' => password]
        } catch (Throwable) {
            throw new HttpException(401, 'User does not exist');
        }

        $userDB_password = $this->query_helper
            ->queryBuilder(User::class)
            ->select('password')
            ->where('email', $token_data['email'])
            ->first()
            ->password;

        if (!$userDB_password) {
            throw new HttpException(401, 'User does not exist');
        }

        if (!Hash::check($token_data['password'], $userDB_password)) {
            throw new HttpException(401, 'User does not exist');
        }

        return response('');
    }
}
