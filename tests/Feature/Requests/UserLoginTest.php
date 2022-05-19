<?php

namespace Requests;

use App\Http\Requests\UserLogin;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    protected $route = 'user-login-validation-test';

    public function setUp(): void
    {
        parent::setUp();

        Route::post($this->route, function (UserLogin $validation) {
            return true;
        });
    }

    public function testSuccess()
    {
        $this->post($this->route, [
            'email' => 'email@ariko.vue',
            'password' => 'Some password'
        ])->assertOk();
    }

    public function testFailed()
    {
        $this->post($this->route, [
            'email' => 'Not Email',
            'password' => 'Some password'
        ])->assertStatus(422);
    }
}
