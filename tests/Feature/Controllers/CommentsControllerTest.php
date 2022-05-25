<?php

namespace Tests\Feature\Controllers;

use App\Http\Requests\CreateComment;
use App\Models\Comment;
use App\Services\TestHelpers\GetModelQueryBuilder;
use App\Services\TestHelpers\interfaces\GetModelQueryBuilderInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\TestCase;

class CommentsControllerTest extends TestCase
{
    public function testCreateSuccess()
    {
        $data = [
            'name' => 'name',
            'email' => 'test@gmail.com',
            'comment' => 'content',
            'post_id' => 1
        ];

        $model_mock = $this->getMockBuilder(Comment::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['fill', 'save'])
            ->getMock();

        $model_mock->expects($this->once())
            ->method('fill')
            ->with($data);

        $model_mock->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $model_mock->name = $data['name'];
        $model_mock->email = $data['email'];
        $model_mock->comment = $data['comment'];
        $model_mock->post_id = $data['post_id'];
        $model_mock->checked = false;
        $model_mock->id = 1;

        $this->instance(Comment::class, $model_mock);

        $request_mock = $this->getMockBuilder(CreateComment::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($data);

        $this->app->instance(
            CreateComment::class,
            $request_mock
        );

        $this->withoutMiddleware(ThrottleRequests::class)
            ->post(route('comment.create'), $data)
            ->assertOk()
            ->assertJson([
                'name' => 'name',
                'email' => 'test@gmail.com',
                'comment' => 'content',
                'post_id' => 1,
                'checked' => false,
                'id' => 1
            ]);
    }

    public function testCreateSaveInDbFailed()
    {
        $data = [
            'name' => 'name',
            'email' => 'test@gmail.com',
            'comment' => 'content',
            'post_id' => 1
        ];

        $model_mock = $this->getMockBuilder(Comment::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['fill', 'save'])
            ->getMock();

        $model_mock->expects($this->once())
            ->method('fill')
            ->with($data);

        $model_mock->expects($this->once())
            ->method('save')
            ->willReturn(false);

        $model_mock->name = $data['name'];
        $model_mock->email = $data['email'];
        $model_mock->comment = $data['comment'];
        $model_mock->post_id = $data['post_id'];

        $this->instance(Comment::class, $model_mock);

        $request_mock = $this->getMockBuilder(CreateComment::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($data);

        $this->app->instance(
            CreateComment::class,
            $request_mock
        );

        $this->withoutMiddleware(ThrottleRequests::class)
            ->post(route('comment.create'), $data)
            ->assertStatus(500);
    }

    public function testCommentsByPost()
    {
        $post_id = 1;
        $result = [
            'data1' => [1, 2, 3],
            'data2' => [1, 2, 3],
            'data3' => [1, 2, 3],
        ];

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['where', 'get'])
            ->getMock();

        $builder_mock->expects($this->once())
            ->method('where')
            ->with('post_id', $post_id)
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('get')
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

        $this->get(route('comments.by-post', $post_id))
            ->assertOk()
            ->assertJson($result);
    }
}
