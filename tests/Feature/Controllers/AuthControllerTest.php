<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Services\TestHelpers\GetModelQueryBuilder;
use App\Services\TestHelpers\interfaces\GetModelQueryBuilderInterface;
use App\Services\TokenValidators\CryptTokenValidator;
use App\Services\TokenValidators\interfaces\AuthTokenValidatorInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function testUserLoginSuccess()
    {
        $user = new \stdClass;
        $user->email = 'ariko@ariko.vue';
        $user->password = 'encrypted_pass';

        $req_data = ['email' => $user->email, 'password' => $user->password];

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
            ->with('email', $user->email)
            ->willReturn($builder_mock);

            $builder_mock->expects($this->once())
            ->method('first')
            ->willReturn($user);

        $query_helper_mock = $this->getMockBuilder(GetModelQueryBuilder::class)
            ->onlyMethods(['queryBuilder'])
            ->getMock();

        $query_helper_mock->expects($this->once())
            ->method('queryBuilder')
            ->with(User::class)
            ->willReturn($builder_mock);

        $this->app->instance(
            GetModelQueryBuilderInterface::class,
            $query_helper_mock
        );

        Hash::shouldReceive('check')
            ->once()
            ->with($req_data['password'], $user->password)
            ->andReturn(true);

        Crypt::shouldReceive('encrypt')
            ->once()
            ->with($req_data)
            ->andReturn('encrypted');

        $res = $this->withoutMiddleware(ThrottleRequests::class)
            ->post(route('auth.login', $req_data))
            ->assertOk();

        $res_content = $res->content();
        $this->assertEquals('encrypted', $res_content);
    }

    public function testUserLoginUserNotExists()
    {
        $user = new \stdClass;
        $user->password = null;

        $req_data = ['email' => 'unexists@ariko.vue', 'password' => 'password'];

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
            ->with('email', $req_data['email'])
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('first')
            ->willReturn($user);

        $query_helper_mock = $this->getMockBuilder(GetModelQueryBuilder::class)
            ->onlyMethods(['queryBuilder'])
            ->getMock();

        $query_helper_mock->expects($this->once())
            ->method('queryBuilder')
            ->with(User::class)
            ->willReturn($builder_mock);

        $this->app->instance(
            GetModelQueryBuilderInterface::class,
            $query_helper_mock
        );

        $this->withoutMiddleware(ThrottleRequests::class)
            ->post(route('auth.login', $req_data))
            ->assertUnauthorized();
    }

    public function testUserLoginPasswordIncorrect()
    {
        $user = new \stdClass;
        $user->email = 'ariko@ariko.vue';
        $user->password = 'encrypted_pass';

        $req_data = ['email' => $user->email, 'password' => 'incorrect password'];

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
            ->with('email', $user->email)
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('first')
            ->willReturn($user);

        $query_helper_mock = $this->getMockBuilder(GetModelQueryBuilder::class)
            ->onlyMethods(['queryBuilder'])
            ->getMock();

        $query_helper_mock->expects($this->once())
            ->method('queryBuilder')
            ->with(User::class)
            ->willReturn($builder_mock);

        $this->app->instance(
            GetModelQueryBuilderInterface::class,
            $query_helper_mock
        );

        Hash::shouldReceive('check')
            ->once()
            ->with($req_data['password'], $user->password)
            ->andReturn(false);

        $this->withoutMiddleware(ThrottleRequests::class)
            ->post(route('auth.login', $req_data))
            ->assertUnauthorized();
    }

    public function testCheckAuthSuccess()
    {
        $token = 'encrypted_token';

        $token_validator_mock = $this->getMockBuilder(CryptTokenValidator::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validate'])
            ->getMock();

        $token_validator_mock->expects($this->once())
            ->method('validate')
            ->with($token)
            ->willReturn(true);

        $this->app->instance(
            AuthTokenValidatorInterface::class,
            $token_validator_mock
        );

        $this->post(route('auth.check', ['token' => $token]))
            ->assertOk();
    }

    public function testCheckAuthInvalidToken()
    {
        $token = 'invalid_token';

        $token_validator_mock = $this->getMockBuilder(CryptTokenValidator::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validate'])
            ->getMock();

        $token_validator_mock->expects($this->once())
            ->method('validate')
            ->with($token)
            ->willReturn(false);

        $this->app->instance(
            AuthTokenValidatorInterface::class,
            $token_validator_mock
        );

        $this->post(route('auth.check', ['token' => $token]))
            ->assertUnauthorized();
    }
}
