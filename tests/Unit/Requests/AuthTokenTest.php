<?php

namespace Requests;

use App\Http\Requests\AuthToken;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthTokenTest extends TestCase
{
    protected $route = 'auth-token-validation-test';

    public function setUp(): void
    {
        parent::setUp();

        Route::post($this->route, function (AuthToken $validation) {
            return true;
        });
    }

    public function testSuccessData()
    {
        $token = Str::random(10);

        $this->post($this->route, ['token' => $token])
            ->assertOk();
    }

    public function testFailedData()
    {
        $this->post($this->route)
            ->assertStatus(422);
    }
}
