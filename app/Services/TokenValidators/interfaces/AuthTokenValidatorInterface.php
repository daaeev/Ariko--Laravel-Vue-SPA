<?php

namespace App\Services\TokenValidators\interfaces;

use Symfony\Component\HttpKernel\Exception\HttpException;

interface AuthTokenValidatorInterface
{
    /**
     * Проверка токена аутентификации и зашифрованные данные на валидность
     *
     * @param string $token
     * @return bool
     * @throws HttpException если токен или зашифрованные данные невалидны
     */
    public function validate(string $token): bool;
}
