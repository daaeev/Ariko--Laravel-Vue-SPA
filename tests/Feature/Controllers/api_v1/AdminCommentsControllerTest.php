<?php

namespace Tests\Feature\Controllers;

use App\Http\Middleware\AuthWithToken;
use App\Http\Requests\PaginationData;
use App\Models\Comment;
use App\Services\TestHelpers\GetModelQueryBuilder;
use App\Services\TestHelpers\interfaces\GetModelQueryBuilderInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminCommentsControllerTest extends TestCase
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
            ->with(Comment::class)
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
            ->get(route('comments.list'))
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
            ->with(Comment::class)
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
            ->get(route('comments.list', ['_limit' => 5]))
            ->assertOk()
            ->assertJson($result);
    }

    public function testDeleteSuccess()
    {
        $model_mock = $this->getMockBuilder(Comment::class)
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
            ->delete(\route('comment.delete', ['model' => $model_mock->id]))
            ->assertOk()
            ->assertJson(['id' => $model_mock->id]);
    }

    public function testDeleteFailed()
    {
        $model_mock = $this->getMockBuilder(Comment::class)
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
            ->delete(\route('comment.delete', ['model' => $model_mock->id]))
            ->assertStatus(500);
    }

    public function testSetCheckedStatusSuccess()
    {
        $model_mock = $this->getMockBuilder(Comment::class)
            ->onlyMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();

        $model_mock->expects($this->once())
            ->method('update')
            ->with(['checked' => true])
            ->willReturn(true);

        $model_mock->id = 1;
        $model_mock->name = 'name';
        $model_mock->email = 'email';
        $model_mock->comment = 'comment';
        $model_mock->checked = true;

        Route::bind('model', function ($value) use ($model_mock) {
            return $model_mock;
        });

        $this->withoutMiddleware(AuthWithToken::class)
            ->patch(\route('comment.checked', ['model' => $model_mock->id]))
            ->assertOk()
            ->assertJson([
                'id' => $model_mock->id,
                'name' => 'name',
                'email' => 'email',
                'comment' => 'comment',
                'checked' => 'checked',
            ]);
    }

    public function testSetCheckedStatusModelUpdateFailed()
    {
        $model_mock = $this->getMockBuilder(Comment::class)
            ->onlyMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();

        $model_mock->expects($this->once())
            ->method('update')
            ->with(['checked' => true])
            ->willReturn(false);

        $model_mock->id = 1;
        $model_mock->name = 'name';
        $model_mock->email = 'email';
        $model_mock->comment = 'comment';
        $model_mock->checked = true;

        Route::bind('model', function ($value) use ($model_mock) {
            return $model_mock;
        });

        $this->withoutMiddleware(AuthWithToken::class)
            ->patch(\route('comment.checked', ['model' => $model_mock->id]))
            ->assertStatus(500);
    }
}
