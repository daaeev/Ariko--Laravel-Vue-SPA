<?php

namespace App\Services\TokenGenerators;

use App\Services\TokenGenerators\Interfaces\TokenGeneratorInterface;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;

class CryptTokenGenerator implements TokenGeneratorInterface
{
    public function generate(User $data): string
    {
        return Crypt::encrypt(['email' => $data->email, 'password' => $data->password]);
    }
}
