<?php

namespace Tests\Feature\Requests;

use App\Http\Requests\AddTagToPost;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;
use Illuminate\Support\Str;

class AddTagToPostTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'add-tag-to-post-validation-test';

    public function setUp(): void
    {
        parent::setUp();

        Route::post($this->route, function (AddTagToPost $validation) {
            return true;
        });
    }

    public function testSuccess()
    {
        $post = Post::factory()->createOne();
        $data = [
            'tag' => 'Tag',
            'post_id' => $post->id
        ];

        $this->post($this->route, $data)
            ->assertOk();
    }

    public function testFailed()
    {
        $data = $this->failedData();

        foreach ($data as $req_data) {
            $this->post($this->route, $req_data)
                ->assertStatus(422);
        }
    }

    public function failedData()
    {
        $post = Post::factory()->createOne();

        return [
            [[
                'tag' => Str::random(256),
                'post_id' => $post->id,
            ]],
            [[
                'post_id' => $post->id,
            ]],
            [[
                'tag' => 'Tag',
                'post_id' => 'undefined',
            ]],
            [[
                'tag' => 'Tag',
            ]],
        ];
    }
}
