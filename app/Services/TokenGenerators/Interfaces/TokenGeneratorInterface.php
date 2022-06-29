<?php

namespace App\Services\TokenGenerators\Interfaces;

use App\Models\User;

interface TokenGeneratorInterface
{
    /**
     * Генерация токена аутентификации
     * 
     * @param User $data
     * @return string
     */
    public function generate(User $data): string;
}
