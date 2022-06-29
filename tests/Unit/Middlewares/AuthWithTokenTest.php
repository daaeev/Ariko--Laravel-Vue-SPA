<?php

namespace Tests\Unit\Middlewares;

use App\Http\Middleware\AuthWithToken;
use App\Services\TokenValidators\CryptTokenValidator;
use App\Services\TokenValidators\interfaces\AuthTokenValidatorInterface;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AuthWithTokenTest extends TestCase
{
    protected string $route = '/auth-with-token-middleware-validation-route';

    public function setUp(): void
    {
        parent::setUp();

        Route::middleware(AuthWithToken::class)
            ->post($this->route, function () {
                return true;
        });
    }

    public function testSuccess()
    {
        $token = 'token';

        $token_valid_mock = $this->getMockBuilder(CryptTokenValidator::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validate'])
            ->getMock();

        $token_valid_mock->expects($this->once())
            ->method('validate')
            ->with($token)
            ->willReturn(true);

        $this->app->instance(
            AuthTokenValidatorInterface::class,
            $token_valid_mock
        );

        $this->post($this->route, [], ['Authorization' => $token])
            ->assertOk();
    }

    public function testFailedWithoutHeader()
    {
        $token_valid_mock = $this->getMockBuilder(CryptTokenValidator::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validate'])
            ->getMock();

        $token_valid_mock->expects($this->once())
            ->method('validate')
            ->with('')
            ->willReturn(false);

        $this->app->instance(
            AuthTokenValidatorInterface::class,
            $token_valid_mock
        );

        $this->post($this->route, [], [])
            ->assertUnauthorized();
    }

    public function testFailedInvalidToken()
    {
        $token = 'invalid_token';

        $token_valid_mock = $this->getMockBuilder(CryptTokenValidator::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validate'])
            ->getMock();

        $token_valid_mock->expects($this->once())
            ->method('validate')
            ->with($token)
            ->willReturn(false);

        $this->app->instance(
            AuthTokenValidatorInterface::class,
            $token_valid_mock
        );

        $this->post($this->route, [], ['Authorization' => $token])
            ->assertUnauthorized();
    }
}
