<?php

namespace Tests\Feature\Controllers;

use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateSuccess()
    {
        $data = [
            'name' => 'name',
            'email' => 'test@gmail.com',
            'message' => 'content'
        ];

        $model_mock = $this->getMockBuilder(Message::class)
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
        $model_mock->message = $data['message'];

        $this->instance(Message::class, $model_mock);

        $this->withoutMiddleware(ThrottleRequests::class)
            ->post(route('message.create'), $data)
            ->assertOk()
            ->assertJson($data);
    }

    public function testCreateSaveInDbFailed()
    {
        $data = [
            'name' => 'name',
            'email' => 'test@gmail.com',
            'message' => 'content'
        ];

        $model_mock = $this->getMockBuilder(Message::class)
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
        $model_mock->message = $data['message'];

        $this->instance(Message::class, $model_mock);

        $this->withoutMiddleware(ThrottleRequests::class)
            ->post(route('message.create'), $data)
            ->assertStatus(500);
    }
}
