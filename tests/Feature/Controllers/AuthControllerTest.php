<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Services\TestHelpers\GetModelQueryBuilder;
use App\Services\TestHelpers\interfaces\GetModelQueryBuilderInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUserLoginSuccess()
    {
        $user = User::factory()->createOne();
        $req_data = ['email' => $user->email, 'password' => 'password'];

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

        $res = $this->withoutMiddleware(ThrottleRequests::class)
            ->post(route('auth.login', $req_data))
            ->assertOk();

        $res_content = Crypt::decrypt($res->content());

        $this->assertEquals($req_data, $res_content);
    }

    public function testUserLoginUserNotExists()
    {
        $req_data = ['email' => 'unexists@ariko.vue', 'password' => 'password'];
        $model_test = new \stdClass;
        $model_test->password = null;

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
            ->willReturn($model_test);

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
        $user = User::factory()->createOne();
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

        $this->withoutMiddleware(ThrottleRequests::class)
            ->post(route('auth.login', $req_data))
            ->assertUnauthorized();
    }

    public function testCheckAuthSuccess()
    {
        $user = User::factory()->createOne();

        $data_to_crypt = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $token = Crypt::encrypt($data_to_crypt);

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

        $this->post(route('auth.check', ['token' => $token]))
            ->assertOk();
    }

    public function testCheckAuthBadToken()
    {
        $token = 'asdwsdawd232jhjds';

        $this->post(route('auth.check', ['token' => $token]))
            ->assertUnauthorized();
    }

    public function testCheckAuthUserNotExists()
    {
        $user = new \stdClass;
        $user->password = null;

        $data_to_crypt = [
            'email' => 'unexists@ariko.vue',
            'password' => 'password',
        ];

        $token = Crypt::encrypt($data_to_crypt);

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
            ->with('email', $data_to_crypt['email'])
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

        $this->post(route('auth.check', ['token' => $token]))
            ->assertUnauthorized();
    }

    public function testCheckAuthIncorrectPassword()
    {
        $user = User::factory()->createOne();

        $data_to_crypt = [
            'email' => $user->email,
            'password' => 'incorrect password',
        ];

        $token = Crypt::encrypt($data_to_crypt);

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

        $this->post(route('auth.check', ['token' => $token]))
            ->assertUnauthorized();
    }
}
