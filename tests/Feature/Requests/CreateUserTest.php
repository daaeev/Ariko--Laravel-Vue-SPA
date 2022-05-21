<?php

namespace Tests\Feature\Requests;

use App\Http\Requests\CreateUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    protected string $route = '/admin-create-user-validation-route';

    public function setUp(): void
    {
        parent::setUp();

        Route::post($this->route, function (CreateUser $validate) {
            return true;
        });
    }

    public function testSuccess()
    {
        $data = [
            'email' => 'unique@arioko.vue',
            'password' => 'password',
        ];

        $this->post($this->route, $data)
            ->assertOk();
    }

    public function testFailed()
    {
        $user = User::factory()->createOne();

        $data = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $this->post($this->route, $data)
            ->assertStatus(422);

        $data = [
            'password' => 'password',
        ];

        $this->post($this->route, $data)
            ->assertStatus(422);

        $data = [
            'email' => 'unique@arioko.vue',
        ];

        $this->post($this->route, $data)
            ->assertStatus(422);
    }
}
