<?php

namespace Tests\Feature\Requests;

use App\Http\Requests\CreateMessage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateMessageTest extends TestCase
{
    protected string $route = '/create-message-validation-route';

    public function setUp(): void
    {
        parent::setUp();

        Route::post($this->route, function (CreateMessage $validate) {
            return true;
        });
    }

    /**
     * @dataProvider successData
     */
    public function testSuccess($data)
    {
        $this->post($this->route, $data)->assertOk();
    }

    /**
     * @dataProvider failedData
     */
    public function testFailed($data)
    {
        $this->post($this->route, $data)->assertStatus(422);
    }

    public function successData()
    {
        return [
            [[
                'name' => 'name',
                'email' => 'test@gmail.com',
                'message' => 'content',
            ]],
        ];
    }

    public function failedData()
    {
        return [
            [[
                'email' => 'test@gmail.com',
                'message' => 'content',
            ]],
            [[
                'name' => 'name',
                'message' => 'content',
            ]],
            [[
                'name' => 'name',
                'email' => 'test@gmail.com',
            ]],
            [[
                'name' => Str::random(51),
                'email' => 'test@gmail.com',
                'message' => 'content',
            ]],
            [[
                'name' => 'name',
                'email' => 'not email',
                'message' => 'content',
            ]],
        ];
    }
}
