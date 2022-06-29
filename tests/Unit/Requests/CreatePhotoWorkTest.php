<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\CreatePhotoWork;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreatePhotoWorkTest extends TestCase
{
    protected $route = 'create-photo-work-validation-test';

    public function setUp(): void
    {
        parent::setUp();

        Route::post($this->route, function (CreatePhotoWork $validation) {
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
        return [
            [[
                'name' => 'name',
                'subject' => 'subject',
                'year' => 2022,
                'client' => 'client',
                'website' => 'https://test.com',
                'title' => 'title',
                'description' => 'description',
            ]],
            [[
                'name' => 'name',
                'subject' => 'subject',
                'year' => '2021-2022',
            ]],
        ];
    }

    public function failedData()
    {
        return [
            [[
                'name' => Str::random(31),
                'subject' => 'subject',
                'year' => 2022,
                'client' => 'client',
                'website' => 'https://test.com',
                'title' => 'title',
                'description' => 'description',
            ]],
            [[
                'subject' => 'subject',
                'year' => 2022,
                'client' => 'client',
                'website' => 'https://test.com',
                'title' => 'title',
                'description' => 'description',
            ]],
            [[
                'name' => 'name',
                'subject' => Str::random(51),
                'year' => 2022,
                'client' => 'client',
                'website' => 'https://test.com',
                'title' => 'title',
                'description' => 'description',
            ]],
            [[
                'name' => 'name',
                'year' => 2022,
                'client' => 'client',
                'website' => 'https://test.com',
                'title' => 'title',
                'description' => 'description',
            ]],
            [[
                'name' => 'name',
                'subject' => 'subject',
                'year' => 'asdadsasdasdad',
                'client' => 'client',
                'website' => 'https://test.com',
                'title' => 'title',
                'description' => 'description',
            ]],
            [[
                'name' => 'name',
                'subject' => 'subject',
                'client' => 'client',
                'website' => 'https://test.com',
                'title' => 'title',
                'description' => 'description',
            ]],
            [[
                'name' => 'name',
                'subject' => 'subject',
                'year' => 2022,
                'client' => Str::random(51),
                'website' => 'https://test.com',
                'title' => 'title',
                'description' => 'description',
            ]],
            [[
                'name' => 'name',
                'subject' => 'subject',
                'year' => 2022,
                'client' => 'client',
                'website' => 'not url',
                'title' => 'title',
                'description' => 'description',
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
