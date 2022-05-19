<?php

namespace Tests\Feature\Controllers;

use App\Models\Post;
use App\Models\PostTag;
use App\Models\Tag;
use App\Services\TestHelpers\GetModelQueryBuilder;
use App\Services\TestHelpers\interfaces\GetModelQueryBuilderInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    public function testPostsList()
    {
        $result = [
            'some_pag_data1' => 1,
            'some_pag_data2' => 2,
            'some_pag_data3' => 3,
            'data' => [],
        ];

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['with', 'paginate'])
            ->getMock();

        $builder_mock->expects($this->once())
            ->method('with')
            ->with('tags')
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('paginate')
            ->with(null)
            ->willReturn($result);

        $query_helper_mock = $this->getMockBuilder(GetModelQueryBuilder::class)
            ->onlyMethods(['queryBuilder'])
            ->getMock();

        $query_helper_mock->expects($this->once())
            ->method('queryBuilder')
            ->with(Post::class)
            ->willReturn($builder_mock);

        $this->app->instance(
            GetModelQueryBuilderInterface::class,
            $query_helper_mock
        );

        $this->get(route('posts.all'))
            ->assertOk()
            ->assertJson($result);
    }

    public function testPostsListWithPerPageParam()
    {
        $perPage = 10;
        $result = [
            'some_pag_data1' => 1,
            'some_pag_data2' => 2,
            'some_pag_data3' => 3,
            'data' => [],
        ];

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['with', 'paginate'])
            ->getMock();

        $builder_mock->expects($this->once())
            ->method('with')
            ->with('tags')
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('paginate')
            ->with($perPage)
            ->willReturn($result);

        $query_helper_mock = $this->getMockBuilder(GetModelQueryBuilder::class)
            ->onlyMethods(['queryBuilder'])
            ->getMock();

        $query_helper_mock->expects($this->once())
            ->method('queryBuilder')
            ->with(Post::class)
            ->willReturn($builder_mock);

        $this->app->instance(
            GetModelQueryBuilderInterface::class,
            $query_helper_mock
        );

        $this->get(route('posts.all', ['_limit' => $perPage]))
            ->assertOk()
            ->assertJson($result);
    }

    public function testSingleIfExist()
    {
        $post_id = 1;
        $result = [
            'var1' => 1,
            'var2' => 2,
            'var3' => 3,
            'var4' => 4,
            'var5' => 5,
        ];

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['with', 'where', 'firstOrFail'])
            ->getMock();

        $builder_mock->expects($this->once())
            ->method('with')
            ->with('tags', 'comments')
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('where')
            ->with('id', $post_id)
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('firstOrFail')
            ->willReturn($result);

        $query_helper_mock = $this->getMockBuilder(GetModelQueryBuilder::class)
            ->onlyMethods(['queryBuilder'])
            ->getMock();

        $query_helper_mock->expects($this->once())
            ->method('queryBuilder')
            ->with(Post::class)
            ->willReturn($builder_mock);

        $this->app->instance(
            GetModelQueryBuilderInterface::class,
            $query_helper_mock
        );

        $this->get(route('posts.single', ['post_id' => $post_id]))
            ->assertOk()
            ->assertJson($result);
    }

    public function testSingleIfNotExist()
    {
        $post_id = 1;

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['with', 'where', 'firstOrFail'])
            ->getMock();

        $builder_mock->expects($this->once())
            ->method('with')
            ->with('tags', 'comments')
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('where')
            ->with('id', $post_id)
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('firstOrFail')
            ->willThrowException(new ModelNotFoundException('', 404));

        $query_helper_mock = $this->getMockBuilder(GetModelQueryBuilder::class)
            ->onlyMethods(['queryBuilder'])
            ->getMock();

        $query_helper_mock->expects($this->once())
            ->method('queryBuilder')
            ->with(Post::class)
            ->willReturn($builder_mock);

        $this->app->instance(
            GetModelQueryBuilderInterface::class,
            $query_helper_mock
        );

        $this->get(route('posts.single', ['post_id' => $post_id]))
            ->assertStatus(404);
    }

    public function testPostsByTag()
    {
        $tag = 'tag';
        $result = [
            'some_pag_data1' => 1,
            'some_pag_data2' => 2,
            'some_pag_data3' => 3,
            'data' => [],
        ];

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['with', 'paginate', 'whereRelation'])
            ->getMock();

        $builder_mock->expects($this->once())
            ->method('with')
            ->with('tags')
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('whereRelation')
            ->with('tags', 'name', $tag)
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('paginate')
            ->with(null)
            ->willReturn($result);

        $query_helper_mock = $this->getMockBuilder(GetModelQueryBuilder::class)
            ->onlyMethods(['queryBuilder'])
            ->getMock();

        $query_helper_mock->expects($this->once())
            ->method('queryBuilder')
            ->with(Post::class)
            ->willReturn($builder_mock);

        $this->app->instance(
            GetModelQueryBuilderInterface::class,
            $query_helper_mock
        );

        $this->get(route('posts.by-tag', ['tag' => $tag]))
            ->assertOk()
            ->assertJson($result);
    }

    public function testPostsByTagWithPerPageVar()
    {
        $perPage = 6;
        $tag = 'tag';
        $result = [
            'some_pag_data1' => 1,
            'some_pag_data2' => 2,
            'some_pag_data3' => 3,
            'data' => [],
        ];

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['with', 'paginate', 'whereRelation'])
            ->getMock();

        $builder_mock->expects($this->once())
            ->method('with')
            ->with('tags')
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('whereRelation')
            ->with('tags', 'name', $tag)
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('paginate')
            ->with($perPage)
            ->willReturn($result);

        $query_helper_mock = $this->getMockBuilder(GetModelQueryBuilder::class)
            ->onlyMethods(['queryBuilder'])
            ->getMock();

        $query_helper_mock->expects($this->once())
            ->method('queryBuilder')
            ->with(Post::class)
            ->willReturn($builder_mock);

        $this->app->instance(
            GetModelQueryBuilderInterface::class,
            $query_helper_mock
        );

        $this->get(route('posts.by-tag', ['tag' => $tag, '_limit' => $perPage]))
            ->assertOk()
            ->assertJson($result);
    }
}
