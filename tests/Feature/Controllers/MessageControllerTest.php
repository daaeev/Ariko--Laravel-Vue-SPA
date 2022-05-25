<?php

namespace Tests\Feature\Controllers;

use App\Http\Requests\CreateMessage;
use App\Models\Message;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    public function testCreateSuccess()
    {
        $data = [
            'name' => 'name',
            'email' => 'test@gmail.com',
            'message' => 'content'
        ];

        $model_mock = $this->getMockBuilder(Message::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['fill', 'save'])
            ->getMock();

        $model_mock->expects($this->once())
            ->method('fill')
            ->with($data);

        $model_mock->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $model_mock->name = $data['name'];
        $model_mock->email = $data['email'];
        $model_mock->message = $data['message'];

        $this->instance(Message::class, $model_mock);

        $request_mock = $this->getMockBuilder(CreateMessage::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($data);

        $this->app->instance(
            CreateMessage::class,
            $request_mock
        );

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
            ->onlyMethods(['fill', 'save'])
            ->getMock();

        $model_mock->expects($this->once())
            ->method('fill')
            ->with($data);

        $model_mock->expects($this->once())
            ->method('save')
            ->willReturn(false);

        $model_mock->name = $data['name'];
        $model_mock->email = $data['email'];
        $model_mock->message = $data['message'];

        $this->instance(Message::class, $model_mock);

        $request_mock = $this->getMockBuilder(CreateMessage::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($data);

        $this->app->instance(
            CreateMessage::class,
            $request_mock
        );

        $this->withoutMiddleware(ThrottleRequests::class)
            ->post(route('message.create'), $data)
            ->assertStatus(500);
    }
}
