<?php

namespace Tests\Feature\Requests;

use App\Http\Requests\CreateComment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;
use Illuminate\Support\Str;

class CreateCommentTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'create-comment-validation-test';

    public function setUp(): void
    {
        parent::setUp();

        Route::post($this->route, function (CreateComment $validation) {
            return true;
        });
    }

    public function testSuccessData()
    {
        $post = Post::factory()->createOne();

        $data = [
            'name' => 'name',
            'email' => 'email@email.ua',
            'comment' => 'comment',
            'post_id' => $post->id,
        ];

        $this->post($this->route, $data)->assertOk();
    }

    public function testFailedData()
    {
        $post = Post::factory()->createOne();
        $data = $this->failedData($post->id);

        foreach ($data as $test) {
            $this->post($this->route, $test)->assertStatus(422);
        }
    }

    protected function failedData($id)
    {
        return [
            [
                'name' => Str::random(51),
                'email' => 'email@email.ua',
                'comment' => 'comment',
                'post_id' => $id,
            ],
            [
                'email' => 'email@email.ua',
                'comment' => 'comment',
                'post_id' => $id,
            ],
            [
                'name' => 'name',
                'email' => 'not email',
                'comment' => 'comment',
                'post_id' => $id,
            ],
            [
                'name' => 'name',
                'comment' => 'comment',
                'post_id' => $id,
            ],
            [
                'name' => 'name',
                'email' => 'email@email.ua',
                'post_id' => $id,
            ],
            [
                'name' => 'name',
                'email' => 'email@email.ua',
                'comment' => 'comment',
                'post_id' => 'unexists id',
            ],
            [
                'name' => 'name',
                'email' => 'email@email.ua',
                'comment' => 'comment',
            ],
        ];
    }
}
