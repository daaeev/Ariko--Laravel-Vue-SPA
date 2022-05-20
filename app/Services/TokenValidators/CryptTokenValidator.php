<?php

namespace App\Services\TokenValidators;

use App\Models\User;
use App\Services\TestHelpers\interfaces\GetModelQueryBuilderInterface;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CryptTokenValidator implements interfaces\AuthTokenValidatorInterface
{
    /**
     * @param GetModelQueryBuilderInterface $query
     */
    public function __construct(protected GetModelQueryBuilderInterface $query)
    {
    }

    /**
     * @inheritDoc
     */
    public function validate(string $token): bool
    {
        if (!$token) {
            return false;
        }

        try {
            $token_data = Crypt::decrypt($token); // ['email' => email, 'password' => password]
        } catch (\Throwable) {
            throw new HttpException(401, 'Invalid token');
        }

        $userDB_password = $this->query
            ->queryBuilder(User::class)
            ->select('password')
            ->where('email', $token_data['email'])
            ->first()
            ->password;

        if (!$userDB_password) {
            return false;
        }

        if (!Hash::check($token_data['password'], $userDB_password)) {
            return false;
        }

        return true;
    }
}
