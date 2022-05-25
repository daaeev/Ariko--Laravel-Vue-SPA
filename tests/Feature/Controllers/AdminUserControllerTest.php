<?php

namespace Tests\Feature\Controllers;

use App\Http\Middleware\AuthWithToken;
use App\Http\Requests\CreateUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserControllerTest extends TestCase
{
    public function testUserCreateSuccess()
    {
        $req_data = ['email' => 'ariko@email.ua', 'password' => 'password'];
        $hashed_pass = 'hashed_pass';

        Hash::shouldReceive('make')
            ->once()
            ->with($req_data['password'])
            ->andReturn($hashed_pass);

        $model_mock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['fill', 'save'])
            ->getMock();

        $model_mock->expects($this->once())
            ->method('fill')
            ->with(['email' => $req_data['email'], 'password' => $hashed_pass]);

        $model_mock->email = $req_data['email'];
        $model_mock->password = $hashed_pass;

        $model_mock->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $this->app->instance(
            User::class,
            $model_mock
        );

        $request_mock = $this->getMockBuilder(CreateUser::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($req_data);

        $this->app->instance(
            CreateUser::class,
            $request_mock
        );

        $this->withoutMiddleware(AuthWithToken::class)
            ->post(route('user.create'), $req_data)
            ->assertJson(['email' => $req_data['email']]);
    }

    public function testUserCreateSaveInDbFailed()
    {
        $req_data = ['email' => 'ariko@email.ua', 'password' => 'password'];
        $hashed_pass = 'hashed_pass';

        Hash::shouldReceive('make')
            ->once()
            ->with($req_data['password'])
            ->andReturn($hashed_pass);

        $model_mock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['fill', 'save'])
            ->getMock();

        $model_mock->expects($this->once())
            ->method('fill')
            ->with(['email' => $req_data['email'], 'password' => $hashed_pass]);

        $model_mock->email = $req_data['email'];
        $model_mock->password = $hashed_pass;

        $model_mock->expects($this->once())
            ->method('save')
            ->willReturn(false);

        $this->app->instance(
            User::class,
            $model_mock
        );

        $request_mock = $this->getMockBuilder(CreateUser::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($req_data);

        $this->app->instance(
            CreateUser::class,
            $request_mock
        );

        $this->withoutMiddleware(AuthWithToken::class)
            ->post(route('user.create'), $req_data)
            ->assertStatus(500);
    }
}
