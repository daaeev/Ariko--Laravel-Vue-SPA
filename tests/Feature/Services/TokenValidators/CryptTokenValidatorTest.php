<?php

namespace Tests\Feature\Services\TokenValidators;

use App\Models\User;
use App\Services\TestHelpers\GetModelQueryBuilder;
use App\Services\TestHelpers\interfaces\GetModelQueryBuilderInterface;
use App\Services\TokenValidators\CryptTokenValidator;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class CryptTokenValidatorTest extends TestCase
{
    public function testValidateSuccess()
    {
        $token = 'token';
        $token_data = ['email' => 'ariko@ariko.vue', 'password' => 'password'];

        $user = new \stdClass;
        $user->password = 'hashed_pass';

        Crypt::shouldReceive('decrypt')
            ->once()
            ->with($token)
            ->andReturn($token_data);

        Hash::shouldReceive('check')
            ->once()
            ->with($token_data['password'], $user->password)
            ->andReturn(true);

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['where', 'first'])
            ->addMethods(['select'])
            ->getMock();

        $builder_mock->expects($this->once())
            ->method('select')
            ->with('password')
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('where')
            ->with('email', $token_data['email'])
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('first')
            ->willReturn($user);

        $query_mock = $this->getMockBuilder(GetModelQueryBuilder::class)
            ->onlyMethods(['queryBuilder'])
            ->disableOriginalConstructor()
            ->getMock();

        $query_mock->expects($this->once())
            ->method('queryBuilder')
            ->with(User::class)
            ->willReturn($builder_mock);

        $this->app->instance(
            GetModelQueryBuilderInterface::class,
            $query_mock
        );

        $instance = app(CryptTokenValidator::class);
        $result = $instance->validate($token);

        $this->assertTrue($result);
    }

    public function testValidateFailedInvalidTokenToDecrypt()
    {
        $token = 'invalid_token';

        Crypt::shouldReceive('decrypt')
            ->once()
            ->with($token)
            ->andThrow(DecryptException::class);

        $this->expectException(HttpException::class);

        $instance = app(CryptTokenValidator::class);
        $instance->validate($token);
    }

    public function testValidateFailedEmptyToken()
    {
        $token = '';

        $instance = app(CryptTokenValidator::class);
        $res = $instance->validate($token);

        $this->assertFalse($res);
    }

    public function testValidateFailedUserNotExists()
    {
        $token = 'token';
        $token_data = ['email' => 'ariko@ariko.vue', 'password' => 'password'];

        $user = new \stdClass;
        $user->password = '';

        Crypt::shouldReceive('decrypt')
            ->once()
            ->with($token)
            ->andReturn($token_data);

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['where', 'first'])
            ->addMethods(['select'])
            ->getMock();

        $builder_mock->expects($this->once())
            ->method('select')
            ->with('password')
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('where')
            ->with('email', $token_data['email'])
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('first')
            ->willReturn($user);

        $query_mock = $this->getMockBuilder(GetModelQueryBuilder::class)
            ->onlyMethods(['queryBuilder'])
            ->disableOriginalConstructor()
            ->getMock();

        $query_mock->expects($this->once())
            ->method('queryBuilder')
            ->with(User::class)
            ->willReturn($builder_mock);

        $this->app->instance(
            GetModelQueryBuilderInterface::class,
            $query_mock
        );

        $instance = app(CryptTokenValidator::class);
        $result = $instance->validate($token);

        $this->assertFalse($result);
    }

    public function testValidateFailedInvalidTokenDataPassword()
    {
        $token = 'token';
        $token_data = ['email' => 'ariko@ariko.vue', 'password' => 'invalid_password'];

        $user = new \stdClass;
        $user->password = 'hashed_pass';

        Crypt::shouldReceive('decrypt')
            ->once()
            ->with($token)
            ->andReturn($token_data);

        Hash::shouldReceive('check')
            ->once()
            ->with($token_data['password'], $user->password)
            ->andReturn(false);

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['where', 'first'])
            ->addMethods(['select'])
            ->getMock();

        $builder_mock->expects($this->once())
            ->method('select')
            ->with('password')
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('where')
            ->with('email', $token_data['email'])
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('first')
            ->willReturn($user);

        $query_mock = $this->getMockBuilder(GetModelQueryBuilder::class)
            ->onlyMethods(['queryBuilder'])
            ->disableOriginalConstructor()
            ->getMock();

        $query_mock->expects($this->once())
            ->method('queryBuilder')
            ->with(User::class)
            ->willReturn($builder_mock);

        $this->app->instance(
            GetModelQueryBuilderInterface::class,
            $query_mock
        );

        $instance = app(CryptTokenValidator::class);
        $result = $instance->validate($token);

        $this->assertFalse($result);
    }
}
