<?php

namespace Tests\Feature\Controllers;

use App\Http\Requests\AuthToken;
use App\Http\Requests\UserLogin;
use App\Models\User;
use App\Services\TestHelpers\GetModelQueryBuilder;
use App\Services\TestHelpers\interfaces\GetModelQueryBuilderInterface;
use App\Services\TokenGenerators\CryptTokenGenerator;
use App\Services\TokenGenerators\Interfaces\TokenGeneratorInterface;
use App\Services\TokenValidators\CryptTokenValidator;
use App\Services\TokenValidators\interfaces\AuthTokenValidatorInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function testUserLoginSuccess()
    {
        $user_mock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();

        $user_mock->email = 'ariko@ariko.vue';
        $user_mock->password = 'encrypted_pass';

        $req_data = ['email' => $user_mock->email, 'password' => $user_mock->password];

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['where', 'firstOrFail'])
            ->addMethods(['select'])
            ->getMock();

        $builder_mock->expects($this->once())
            ->method('select')
            ->with('password', 'email')
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('where')
            ->with('email', $user_mock->email)
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('firstOrFail')
            ->willReturn($user_mock);

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

        $request_mock = $this->getMockBuilder(UserLogin::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($req_data);

        $this->app->instance(
            UserLogin::class,
            $request_mock
        );

        Hash::shouldReceive('check')
            ->once()
            ->with($req_data['password'], $user_mock->password)
            ->andReturn(true);

        $tokengen_mock = $this->getMockBuilder(CryptTokenGenerator::class)
            ->onlyMethods(['generate'])
            ->disableOriginalConstructor()
            ->getMock();

        $tokengen_mock->expects($this->once())
            ->method('generate')
            ->with($user_mock)
            ->willReturn('encrypted');

        $this->app->instance(
            TokenGeneratorInterface::class,
            $tokengen_mock
        );

        $res = $this->withoutMiddleware(ThrottleRequests::class)
            ->post(route('auth.login', $req_data))
            ->assertOk();

        $res_content = $res->content();
        $this->assertEquals('encrypted', $res_content);
    }

    public function testUserLoginUserNotExists()
    {
        $req_data = ['email' => 'unexists@ariko.vue', 'password' => 'password'];

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['where', 'firstOrFail'])
            ->addMethods(['select'])
            ->getMock();

        $builder_mock->expects($this->once())
            ->method('select')
            ->with('password', 'email')
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('where')
            ->with('email', $req_data['email'])
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('firstOrFail')
            ->willThrowException(new ModelNotFoundException);

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

        $request_mock = $this->getMockBuilder(UserLogin::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($req_data);

        $this->app->instance(
            UserLogin::class,
            $request_mock
        );

        $this->withoutMiddleware(ThrottleRequests::class)
            ->post(route('auth.login', $req_data))
            ->assertNotFound();
    }

    public function testUserLoginPasswordIncorrect()
    {
        $user_mock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();

        $user_mock->email = 'ariko@ariko.vue';
        $user_mock->password = 'encrypted_pass';

        $req_data = ['email' => $user_mock->email, 'password' => 'incorrect password'];

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['where', 'firstOrFail'])
            ->addMethods(['select'])
            ->getMock();

        $builder_mock->expects($this->once())
            ->method('select')
            ->with('password', 'email')
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('where')
            ->with('email', $user_mock->email)
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('firstOrFail')
            ->willReturn($user_mock);

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

        $request_mock = $this->getMockBuilder(UserLogin::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($req_data);

        $this->app->instance(
            UserLogin::class,
            $request_mock
        );

        Hash::shouldReceive('check')
            ->once()
            ->with($req_data['password'], $user_mock->password)
            ->andReturn(false);

        $this->withoutMiddleware(ThrottleRequests::class)
            ->post(route('auth.login', $req_data))
            ->assertNotFound();
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

        $request_mock = $this->getMockBuilder(AuthToken::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->with('token')
            ->willReturn($token);

        $this->app->instance(
            AuthToken::class,
            $request_mock
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

        $request_mock = $this->getMockBuilder(AuthToken::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->with('token')
            ->willReturn($token);

        $this->app->instance(
            AuthToken::class,
            $request_mock
        );

        $this->post(route('auth.check', ['token' => $token]))
            ->assertUnauthorized();
    }
}
