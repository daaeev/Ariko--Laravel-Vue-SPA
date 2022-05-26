<?php

namespace Tests\Feature\Requests;

use App\Http\Requests\CreateVideoWork;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CreateVideoWorkTest extends TestCase
{
    protected $route = '/create-video-work-validation-test';

    public function setUp(): void
    {
        parent::setUp();

        Route::post($this->route, function (CreateVideoWork $validation) {
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

    public function successData()
    {
        $video = new UploadedFile(
            dirname(dirname(__DIR__)) . '/TestFiles/video.mp4',
            'image.png',
            'image/*',
            null,
            true
        );

        return [
            [[
                'name' => 'name',
                'subject' => 'subject',
                'year' => 2022,
                'client' => 'client',
                'website' => 'https://test.com',
                'title' => 'title',
                'description' => 'description',
                'video' => $video
            ]],
            [[
                'name' => 'name',
                'subject' => 'subject',
                'year' => '2021-2022',
                'video' => $video
            ]],
        ];
    }

    public function failedData()
    {
        $video = new UploadedFile(
            dirname(dirname(__DIR__)) . '/TestFiles/video.mp4',
            'image.png',
            'image/*',
            null,
            true
        );

        return [
            [[
                'name' => Str::random(31),
                'subject' => 'subject',
                'year' => 2022,
                'client' => 'client',
                'website' => 'https://test.com',
                'title' => 'title',
                'description' => 'description',
                'video' => $video
            ]],
            [[
                'subject' => 'subject',
                'year' => 2022,
                'client' => 'client',
                'website' => 'https://test.com',
                'title' => 'title',
                'description' => 'description',
                'video' => $video
            ]],
            [[
                'name' => 'name',
                'subject' => Str::random(51),
                'year' => 2022,
                'client' => 'client',
                'website' => 'https://test.com',
                'title' => 'title',
                'description' => 'description',
                'video' => $video
            ]],
            [[
                'name' => 'name',
                'year' => 2022,
                'client' => 'client',
                'website' => 'https://test.com',
                'title' => 'title',
                'description' => 'description',
                'video' => $video
            ]],
            [[
                'name' => 'name',
                'subject' => 'subject',
                'year' => 'asdadsasdasdad',
                'client' => 'client',
                'website' => 'https://test.com',
                'title' => 'title',
                'description' => 'description',
                'video' => $video
            ]],
            [[
                'name' => 'name',
                'subject' => 'subject',
                'client' => 'client',
                'website' => 'https://test.com',
                'title' => 'title',
                'description' => 'description',
                'video' => $video
            ]],
            [[
                'name' => 'name',
                'subject' => 'subject',
                'year' => 2022,
                'client' => Str::random(51),
                'website' => 'https://test.com',
                'title' => 'title',
                'description' => 'description',
                'video' => $video
            ]],
            [[
                'name' => 'name',
                'subject' => 'subject',
                'year' => 2022,
                'client' => 'client',
                'website' => 'not url',
                'title' => 'title',
                'description' => 'description',
                'video' => $video
            ]],
            [[
                'name' => 'name',
                'subject' => 'subject',
                'year' => 2022,
                'client' => 'client',
                'website' => 'https://test.com',
                'title' => Str::random(256),
                'description' => 'description',
                'video' => 'not video',
            ]],
            [[
                'name' => 'name',
                'subject' => 'subject',
                'year' => 2022,
                'client' => 'client',
                'website' => 'https://test.com',
                'title' => Str::random(256),
                'description' => 'description',
            ]],
        ];
    }
}
