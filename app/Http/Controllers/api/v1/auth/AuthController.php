<?php

namespace App\Http\Controllers\api\v1\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthToken;
use App\Http\Requests\UserLogin;
use App\Models\User;
use App\Services\TokenValidators\interfaces\AuthTokenValidatorInterface;
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
        $data = $validate->validated();

        $userDB_password = $this->query_helper
            ->queryBuilder(User::class)
            ->select('password')
            ->where('email', $data['email'])
            ->first()
            ->password;

        if (!$userDB_password) {
            throw new HttpException(401, 'User does not exist');
        }

        if (!Hash::check($data['password'], $userDB_password)) {
            throw new HttpException(401, 'User does not exist');
        }

        $encrypted_data = Crypt::encrypt(['email' => $data['email'], 'password' => $data['password']]);

        return response($encrypted_data);
    }

    /**
     * Проверка токена аутентификации
     *
     * @param AuthTokenValidatorInterface $token_validator
     * @param AuthToken $validate
     * @return \Illuminate\Http\Response
     * @throws HttpException
     */
    public function authCheck(
        AuthTokenValidatorInterface $token_validator,
        AuthToken $validate
    )
    {
        $token = $validate->validated('token');

        if ($token_validator->validate($token)) {
            return response('');
        } else {
            throw new HttpException(401, 'Invalid token');
        }
    }
}
