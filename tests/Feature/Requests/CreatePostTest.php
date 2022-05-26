<?php

namespace Tests\Feature\Requests;

use App\Http\Requests\CreatePost;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;
use Illuminate\Support\Str;

class CreatePostTest extends TestCase
{
    protected string $route = '/admin-create-post-validation-route';

    public function setUp(): void
    {
        parent::setUp();

        Route::post($this->route, function (CreatePost $validate) {
            return true;
        });
    }

    /**
     * @dataProvider successData
     */
    public function testSuccess($data)
    {
        $this->post($this->route, $data)
            ->assertOk();
    }

    /**
     * @dataProvider failedData
     */
    public function testFailed($data)
    {
        $this->post($this->route, $data)
            ->assertStatus(422);
    }

    public function failedData()
    {
        $image = new UploadedFile(
            dirname(dirname(__DIR__)) . '/TestFiles/image.png',
            'image.png',
            'image/*',
            null,
            true
        );

        return [
            [[
                'title' => Str::random(256),
                'content' => 'content',
                'main_image' => $image,
                'preview_image' => $image,
            ]],
            [[
                'content' => 'content',
                'main_image' => $image,
                'preview_image' => $image,
            ]],
            [[
                'title' => 'title',
                'main_image' => $image,
                'preview_image' => $image,
            ]],
            [[
                'title' => 'title',
                'content' => 'content',
                'main_image' => 'not image',
                'preview_image' => $image,
            ]],
            [[
                'title' => 'title',
                'content' => 'content',
                'preview_image' => $image,
            ]],
            [[
                'title' => 'title',
                'content' => 'content',
                'main_image' => $image,
                'preview_image' => 'not image',
            ]],
        ];
    }

    public function successData()
    {
        $image = new UploadedFile(
            dirname(dirname(__DIR__)) . '/TestFiles/image.png',
            'image.png',
            'image/*',
            null,
            true
        );

        return [
            [[
                'title' => 'title',
                'content' => 'content',
                'main_image' => $image,
            ]],
            [[
                'title' => 'title',
                'content' => 'content',
                'main_image' => $image,
                'preview_image' => $image,
            ]],
        ];
    }
}
