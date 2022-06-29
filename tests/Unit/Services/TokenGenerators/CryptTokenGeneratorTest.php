<?php

namespace Tests\Unit\Services\TokenGenerators;

use App\Services\TokenGenerators\CryptTokenGenerator;
use Illuminate\Support\Facades\Crypt;
use PHPUnit\Framework\TestCase;
use App\Models\User;

class CryptTokenGeneratorTest extends TestCase
{
    public function testCheckFacadeHasCalled()
    {
        $instance = app(CryptTokenGenerator::class);

        $user_mock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();

        $user_mock->email = 'ariko@ariko.vue';
        $user_mock->password = 'encrypted_pass';

        Crypt::shouldReceive('encrypt')
            ->once()
            ->with(['email' => $user_mock->email, 'password' => $user_mock->password])
            ->andReturn('encrypted_token');

        $result = $instance->generate($user_mock);

        $this->assertEquals('encrypted_token', $result);
    }
}
