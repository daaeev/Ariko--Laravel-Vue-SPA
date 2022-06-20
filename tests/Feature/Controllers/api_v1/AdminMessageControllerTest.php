<?php

namespace Tests\Feature\Controllers;

use App\Http\Middleware\AuthWithToken;
use App\Http\Requests\PaginationData;
use App\Models\Message;
use App\Services\TestHelpers\GetModelQueryBuilder;
use App\Services\TestHelpers\interfaces\GetModelQueryBuilderInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminMessageControllerTest extends TestCase
{
    public function testMessagesListWithoutPerPageParam()
    {
        $result = [
            'pag_data1',
            'pag_data2',
            'pag_data3',
            'db_data',
        ];

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->onlyMethods(['paginate'])
            ->disableOriginalConstructor()
            ->getMock();

        $builder_mock->expects($this->once())
            ->method('paginate')
            ->with(null)
            ->willReturn($result);

        $query_helper_mock = $this->getMockBuilder(GetModelQueryBuilder::class)
            ->onlyMethods(['queryBuilder'])
            ->getMock();

        $query_helper_mock->expects($this->once())
            ->method('queryBuilder')
            ->with(Message::class)
            ->willReturn($builder_mock);

        $this->app->instance(
            GetModelQueryBuilderInterface::class,
            $query_helper_mock
        );

        $request_mock = $this->getMockBuilder(PaginationData::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->with('_limit')
            ->willReturn(null);

        $this->instance(PaginationData::class, $request_mock);

        $this->withoutMiddleware(AuthWithToken::class)
            ->get(route('messages.list'))
            ->assertOk()
            ->assertJson($result);
    }

    public function testMessagesListWithPerPageParam()
    {
        $result = [
            'pag_data1',
            'pag_data2',
            'pag_data3',
            'db_data',
        ];

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->onlyMethods(['paginate'])
            ->disableOriginalConstructor()
            ->getMock();

        $builder_mock->expects($this->once())
            ->method('paginate')
            ->with(5)
            ->willReturn($result);

        $query_helper_mock = $this->getMockBuilder(GetModelQueryBuilder::class)
            ->onlyMethods(['queryBuilder'])
            ->getMock();

        $query_helper_mock->expects($this->once())
            ->method('queryBuilder')
            ->with(Message::class)
            ->willReturn($builder_mock);

        $this->app->instance(
            GetModelQueryBuilderInterface::class,
            $query_helper_mock
        );

        $request_mock = $this->getMockBuilder(PaginationData::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->with('_limit')
            ->willReturn(5);

        $this->instance(PaginationData::class, $request_mock);

        $this->withoutMiddleware(AuthWithToken::class)
            ->get(route('messages.list', ['_limit' => 5]))
            ->assertOk()
            ->assertJson($result);
    }

    public function testDeleteModelSuccess()
    {
        $model_mock = $this->getMockBuilder(Message::class)
            ->onlyMethods(['delete'])
            ->disableOriginalConstructor()
            ->getMock();

        $model_mock->expects($this->once())
            ->method('delete')
            ->willReturn(true);

        $model_mock->id = 1;

        Route::bind('model', function ($value) use ($model_mock) {
            return $model_mock;
        });

        $this->withoutMiddleware(AuthWithToken::class)
            ->delete(route('messages.delete', ['model' => $model_mock->id]))
            ->assertOk()
            ->assertJson(['id' => $model_mock->id]);
    }

    public function testDeleteModelFailed()
    {
        $model_mock = $this->getMockBuilder(Message::class)
            ->onlyMethods(['delete'])
            ->disableOriginalConstructor()
            ->getMock();

        $model_mock->expects($this->once())
            ->method('delete')
            ->willReturn(false);

        $model_mock->id = 1;

        Route::bind('model', function ($value) use ($model_mock) {
            return $model_mock;
        });

        $this->withoutMiddleware(AuthWithToken::class)
            ->delete(route('messages.delete', ['model' => $model_mock->id]))
            ->assertStatus(500);
    }
}
