<?php

namespace Tests\Feature\Controllers;

use App\Models\Post;
use App\Models\PostTag;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testPostsListIfEmpty()
    {
        $this->json('get', route('posts.all'))
            ->assertOk()
            ->assertJson([]);
    }

    public function testPostsListIfExists()
    {
        $posts = Post::factory(2)->create();

        $this->json('get', route('posts.all'))
            ->assertOk()
            ->assertJson(['data' => [$posts[0]->attributesToArray(), $posts[1]->attributesToArray()]]);
    }

    public function testPostListPagination()
    {
        Post::factory(2)->create();

        $this->json('get', route('posts.all'))
            ->assertOk()
            ->assertJson([
                'per_page' => 15,
                'current_page' => 1,
                'last_page' => 1,
            ]);

        $this->json('get', route('posts.all', ['_limit' => 1]))
            ->assertOk()
            ->assertJson([
                'per_page' => 1,
                'current_page' => 1,
                'last_page' => 2,
            ]);

        $this->json('get', route('posts.all', ['_limit' => 2]))
            ->assertOk()
            ->assertJson([
                'per_page' => 2,
                'current_page' => 1,
                'last_page' => 1,
            ]);

        $this->json('get', route('posts.all', ['_limit' => 1, 'page' => 2]))
            ->assertOk()
            ->assertJson([
                'per_page' => 1,
                'current_page' => 2,
                'last_page' => 2,
            ]);
    }

    public function testSingleIfNotExists()
    {
        $this->json('get', route('posts.single', ['post_id' => 1]))
            ->assertStatus(404);
    }

    public function testSingleIfExists()
    {
        $post = Post::factory()->createOne();

        $this->json('get', route('posts.single', ['post_id' => $post->id]))
            ->assertOk()
            ->assertJson($post->attributesToArray());
    }

    public function testPostsByTagIfNotExists()
    {
        $this->json('get', route('posts.by-tag', ['tag' => 'undefined tag']))
            ->assertOk()
            ->assertJson([
                'data' => [],
            ]);
    }

    public function testPostsByTagIfExists()
    {
        $posts = Post::factory(2)->create();
        $tag = Tag::factory()->createOne();
        PostTag::factory()->createOne(['post_id' => $posts[0]->id, 'tag_id' => $tag->id]);

        $this->json('get', route('posts.by-tag', ['tag' => $tag->name]))
        ->assertOk()
        ->assertJson([
            'data' => [$posts[0]->attributesToArray()],
        ]);
    }

    public function testPostsByTagPagination()
    {
        $posts = Post::factory(2)->create();
        $tag = Tag::factory()->createOne();
        PostTag::factory()->createOne(['post_id' => $posts[0]->id, 'tag_id' => $tag->id]);
        PostTag::factory()->createOne(['post_id' => $posts[1]->id, 'tag_id' => $tag->id]);

        $this->json('get', route('posts.by-tag', ['tag' => $tag->name]))
            ->assertOk()
            ->assertJson([
                'per_page' => 15,
                'current_page' => 1,
                'last_page' => 1,
            ]);

        $this->json('get', route('posts.by-tag', ['_limit' => 1, 'tag' => $tag->name]))
            ->assertOk()
            ->assertJson([
                'per_page' => 1,
                'current_page' => 1,
                'last_page' => 2,
            ]);

        $this->json('get', route('posts.by-tag', ['_limit' => 2, 'tag' => $tag->name]))
            ->assertOk()
            ->assertJson([
                'per_page' => 2,
                'current_page' => 1,
                'last_page' => 1,
            ]);

        $this->json('get', route('posts.by-tag', ['_limit' => 1, 'page' => 2, 'tag' => $tag->name]))
            ->assertOk()
            ->assertJson([
                'per_page' => 1,
                'current_page' => 2,
                'last_page' => 2,
            ]);
    }
}
