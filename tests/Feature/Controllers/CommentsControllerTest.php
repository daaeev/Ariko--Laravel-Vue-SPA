<?php

namespace Tests\Feature\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Services\TestHelpers\GetModelQueryBuilder;
use App\Services\TestHelpers\interfaces\GetModelQueryBuilderInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateSuccess()
    {
        $post_id = Post::factory()->createOne()->id;

        $data = [
            'name' => 'name',
            'email' => 'test@gmail.com',
            'comment' => 'content',
            'post_id' => $post_id
        ];

        $model_mock = $this->getMockBuilder(Comment::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['setRawAttributes', 'save'])
            ->getMock();

        $model_mock->expects($this->once())
            ->method('setRawAttributes')
            ->with($data);

        $model_mock->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $model_mock->name = $data['name'];
        $model_mock->email = $data['email'];
        $model_mock->comment = $data['comment'];
        $model_mock->post_id = $data['post_id'];
        $model_mock->checked = false;

        $this->instance(Comment::class, $model_mock);

        $this->post(route('comment.create'), $data)
            ->assertOk()
            ->assertJson([
                'name' => 'name',
                'email' => 'test@gmail.com',
                'comment' => 'content',
                'post_id' => $post_id,
                'checked' => false,
            ]);
    }

    public function testCreateSaveInDbFailed()
    {
        $post_id = Post::factory()->createOne()->id;

        $data = [
            'name' => 'name',
            'email' => 'test@gmail.com',
            'comment' => 'content',
            'post_id' => $post_id
        ];

        $model_mock = $this->getMockBuilder(Comment::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['setRawAttributes', 'save'])
            ->getMock();

        $model_mock->expects($this->once())
            ->method('setRawAttributes')
            ->with($data);

        $model_mock->expects($this->once())
            ->method('save')
            ->willReturn(false);

        $model_mock->name = $data['name'];
        $model_mock->email = $data['email'];
        $model_mock->comment = $data['comment'];
        $model_mock->post_id = $data['post_id'];

        $this->instance(Comment::class, $model_mock);

        $this->post(route('comment.create'), $data)->assertStatus(500);
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
