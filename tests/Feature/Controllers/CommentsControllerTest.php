<?php

namespace Tests\Feature\Controllers;

use App\Models\Comment;
use App\Models\Post;
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

    public function testCommentsByPostIfNotExists()
    {
        $this->get(route('comments.by-post', 1))
            ->assertOk()
            ->assertJson([]);
    }

    public function testCommentsByPostIfExists()
    {
        $post_id = Post::factory()->createOne()->id;
        $comments = Comment::factory(2)->create(['post_id' => $post_id]);
        
        $this->get(route('comments.by-post', $post_id))
            ->assertOk()
            ->assertJson([$comments[0]->attributesToArray(), $comments[1]->attributesToArray()]);
    }
}
