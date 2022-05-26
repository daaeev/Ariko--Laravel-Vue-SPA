<?php

namespace Tests\Feature\Controllers;

use App\Http\Middleware\AuthWithToken;
use App\Http\Requests\CreatePost;
use App\Models\Post;
use App\Services\FileProcessing\FileNameGenerators\FileNameGenerator;
use App\Services\FileProcessing\FileNameGenerators\Interfaces\FileNameGeneratorInterface;
use App\Services\FileProcessing\FileProcessing;
use App\Services\FileProcessing\Interfaces\FileProcessingInterface;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminPostControllerTest extends TestCase
{
    public function testCreatePostWithMainImageSuccess()
    {
        $image = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $data = [
            'title' => 'title',
            'content' => 'content',
            'main_image' => $image
        ];

        $request_mock = $this->getMockBuilder(CreatePost::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($data);

        $this->app->instance(
            CreatePost::class,
            $request_mock
        );

        $fngen_mock = $this->getMockBuilder(FileNameGenerator::class)
            ->getMock();

        $this->app->instance(
            FileNameGeneratorInterface::class,
            $fngen_mock
        );

        $fp_mock = $this->getMockBuilder(FileProcessing::class)
            ->onlyMethods(['saveFile', 'disk', 'directory'])
            ->getMock();

        $fp_mock->expects($this->once())
            ->method('disk')
            ->with('public')
            ->willReturnSelf();

        $fp_mock->expects($this->once())
            ->method('directory')
            ->with('posts_previews')
            ->willReturnSelf();

        $fp_mock->expects($this->once())
            ->method('saveFile')
            ->with($image, $fngen_mock)
            ->willReturn('saved_image.png');

        $this->app->instance(
            FileProcessingInterface::class,
            $fp_mock
        );

        $model_mock = $this->getMockBuilder(Post::class)
            ->onlyMethods(['fill', 'save'])
            ->disableOriginalConstructor()
            ->getMock();

        $model_mock->expects($this->once())
            ->method('fill')
            ->with([
                'title' => 'title',
                'content' => 'content',
                'main_image' => 'saved_image.png'
            ]);

        $model_mock->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $model_mock->title = 'title';
        $model_mock->content = 'content';
        $model_mock->main_image = 'saved_image.png';
        $model_mock->id = 1;

        $this->app->instance(
            Post::class,
            $model_mock
        );

        $this->withoutMiddleware(AuthWithToken::class)
            ->post(route('post.create'), $data)
            ->assertOk()
            ->assertJson([
                'title' => 'title',
                'content' => 'content',
                'main_image' => 'saved_image.png',
                'id' => 1
            ]);
    }

    public function testCreatePostWithMainImageSaveFailed()
    {
        $image = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $data = [
            'title' => 'title',
            'content' => 'content',
            'main_image' => $image
        ];

        $request_mock = $this->getMockBuilder(CreatePost::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($data);

        $this->app->instance(
            CreatePost::class,
            $request_mock
        );

        $fngen_mock = $this->getMockBuilder(FileNameGenerator::class)
            ->getMock();

        $this->app->instance(
            FileNameGeneratorInterface::class,
            $fngen_mock
        );

        $fp_mock = $this->getMockBuilder(FileProcessing::class)
            ->onlyMethods(['saveFile', 'disk', 'directory'])
            ->getMock();

        $fp_mock->expects($this->once())
            ->method('disk')
            ->with('public')
            ->willReturnSelf();

        $fp_mock->expects($this->once())
            ->method('directory')
            ->with('posts_previews')
            ->willReturnSelf();

        $fp_mock->expects($this->once())
            ->method('saveFile')
            ->with($image, $fngen_mock)
            ->willReturn(false);

        $this->app->instance(
            FileProcessingInterface::class,
            $fp_mock
        );

        $this->withoutMiddleware(AuthWithToken::class)
            ->post(route('post.create'), $data)
            ->assertStatus(500);
    }

    public function testCreatePostWithMainModelSaveFailed()
    {
        $image = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $data = [
            'title' => 'title',
            'content' => 'content',
            'main_image' => $image
        ];

        $request_mock = $this->getMockBuilder(CreatePost::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($data);

        $this->app->instance(
            CreatePost::class,
            $request_mock
        );

        $fngen_mock = $this->getMockBuilder(FileNameGenerator::class)
            ->getMock();

        $this->app->instance(
            FileNameGeneratorInterface::class,
            $fngen_mock
        );

        $fp_mock = $this->getMockBuilder(FileProcessing::class)
            ->onlyMethods(['saveFile', 'deleteFile', 'disk', 'directory'])
            ->getMock();

        $fp_mock->expects($this->once())
            ->method('disk')
            ->with('public')
            ->willReturnSelf();

        $fp_mock->expects($this->once())
            ->method('directory')
            ->with('posts_previews')
            ->willReturnSelf();

        $fp_mock->expects($this->once())
            ->method('saveFile')
            ->with($image, $fngen_mock)
            ->willReturn('saved_image.png');

        $fp_mock->expects($this->once())
            ->method('deleteFile')
            ->with('saved_image.png')
            ->willReturn(true);

        $this->app->instance(
            FileProcessingInterface::class,
            $fp_mock
        );

        $model_mock = $this->getMockBuilder(Post::class)
            ->onlyMethods(['fill', 'save'])
            ->disableOriginalConstructor()
            ->getMock();

        $model_mock->expects($this->once())
            ->method('fill')
            ->with([
                'title' => 'title',
                'content' => 'content',
                'main_image' => 'saved_image.png'
            ]);

        $model_mock->expects($this->once())
            ->method('save')
            ->willReturn(false);

        $model_mock->title = 'title';
        $model_mock->content = 'content';
        $model_mock->main_image = 'saved_image.png';
        $model_mock->id = 1;

        $this->app->instance(
            Post::class,
            $model_mock
        );

        $this->withoutMiddleware(AuthWithToken::class)
            ->post(route('post.create'), $data)
            ->assertStatus(500);
    }

    public function testCreatePostWithTwoImagesSuccess()
    {
        $image1 = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $image2 = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $data = [
            'title' => 'title',
            'content' => 'content',
            'main_image' => $image1,
            'preview_image' => $image2,
        ];

        $request_mock = $this->getMockBuilder(CreatePost::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($data);

        $this->app->instance(
            CreatePost::class,
            $request_mock
        );

        $fngen_mock = $this->getMockBuilder(FileNameGenerator::class)
            ->getMock();

        $this->app->instance(
            FileNameGeneratorInterface::class,
            $fngen_mock
        );

        $fp_mock = $this->getMockBuilder(FileProcessing::class)
            ->onlyMethods(['saveFile', 'disk', 'directory'])
            ->getMock();

        $fp_mock->expects($this->once())
            ->method('disk')
            ->with('public')
            ->willReturnSelf();

        $fp_mock->expects($this->once())
            ->method('directory')
            ->with('posts_previews')
            ->willReturnSelf();

        $fp_mock->expects($this->at(2))
            ->method('saveFile')
            ->with($image1, $fngen_mock)
            ->willReturn('saved_image1.png');

        $fp_mock->expects($this->at(3))
            ->method('saveFile')
            ->with($image2, $fngen_mock)
            ->willReturn('saved_image2.png');

        $this->app->instance(
            FileProcessingInterface::class,
            $fp_mock
        );

        $model_mock = $this->getMockBuilder(Post::class)
            ->onlyMethods(['fill', 'save'])
            ->disableOriginalConstructor()
            ->getMock();

        $model_mock->expects($this->once())
            ->method('fill')
            ->with([
                'title' => 'title',
                'content' => 'content',
                'main_image' => 'saved_image1.png',
                'preview_image' => 'saved_image2.png',
            ]);

        $model_mock->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $model_mock->title = 'title';
        $model_mock->content = 'content';
        $model_mock->main_image = 'saved_image1.png';
        $model_mock->preview_image = 'saved_image2.png';
        $model_mock->id = 1;

        $this->app->instance(
            Post::class,
            $model_mock
        );

        $this->withoutMiddleware(AuthWithToken::class)
            ->post(route('post.create'), $data)
            ->assertOk()
            ->assertJson([
                'title' => 'title',
                'content' => 'content',
                'main_image' => 'saved_image1.png',
                'preview_image' => 'saved_image2.png',
                'id' => 1
            ]);
    }

    public function testCreatePostWithTwoImagesSecondSaveFailed()
    {
        $image1 = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $image2 = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $data = [
            'title' => 'title',
            'content' => 'content',
            'main_image' => $image1,
            'preview_image' => $image2,
        ];

        $request_mock = $this->getMockBuilder(CreatePost::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($data);

        $this->app->instance(
            CreatePost::class,
            $request_mock
        );

        $fngen_mock = $this->getMockBuilder(FileNameGenerator::class)
            ->getMock();

        $this->app->instance(
            FileNameGeneratorInterface::class,
            $fngen_mock
        );

        $fp_mock = $this->getMockBuilder(FileProcessing::class)
            ->onlyMethods(['saveFile', 'disk', 'directory', 'deleteFile'])
            ->getMock();

        $fp_mock->expects($this->once())
            ->method('disk')
            ->with('public')
            ->willReturnSelf();

        $fp_mock->expects($this->once())
            ->method('directory')
            ->with('posts_previews')
            ->willReturnSelf();

        $fp_mock->expects($this->at(2))
            ->method('saveFile')
            ->with($image1, $fngen_mock)
            ->willReturn('saved_image1.png');

        $fp_mock->expects($this->at(3))
            ->method('saveFile')
            ->with($image2, $fngen_mock)
            ->willReturn(false);

        $fp_mock->expects($this->once())
            ->method('deleteFile')
            ->with('saved_image1.png')
            ->willReturn(true);

        $this->app->instance(
            FileProcessingInterface::class,
            $fp_mock
        );

        $this->withoutMiddleware(AuthWithToken::class)
            ->post(route('post.create'), $data)
            ->assertStatus(500);
    }

    public function testCreatePostWithTwoImagesModelSaveFailed()
    {
        $image1 = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $image2 = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $data = [
            'title' => 'title',
            'content' => 'content',
            'main_image' => $image1,
            'preview_image' => $image2,
        ];

        $request_mock = $this->getMockBuilder(CreatePost::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($data);

        $this->app->instance(
            CreatePost::class,
            $request_mock
        );

        $fngen_mock = $this->getMockBuilder(FileNameGenerator::class)
            ->getMock();

        $this->app->instance(
            FileNameGeneratorInterface::class,
            $fngen_mock
        );

        $fp_mock = $this->getMockBuilder(FileProcessing::class)
            ->onlyMethods(['saveFile', 'disk', 'directory', 'deleteFile'])
            ->getMock();

        $fp_mock->expects($this->once())
            ->method('disk')
            ->with('public')
            ->willReturnSelf();

        $fp_mock->expects($this->once())
            ->method('directory')
            ->with('posts_previews')
            ->willReturnSelf();

        $fp_mock->expects($this->at(2))
            ->method('saveFile')
            ->with($image1, $fngen_mock)
            ->willReturn('saved_image1.png');

        $fp_mock->expects($this->at(3))
            ->method('saveFile')
            ->with($image2, $fngen_mock)
            ->willReturn('saved_image2.png');

        $fp_mock->expects($this->at(4))
            ->method('deleteFile')
            ->with('saved_image1.png');

        $fp_mock->expects($this->at(5))
            ->method('deleteFile')
            ->with('saved_image2.png');

        $this->app->instance(
            FileProcessingInterface::class,
            $fp_mock
        );

        $model_mock = $this->getMockBuilder(Post::class)
            ->onlyMethods(['fill', 'save'])
            ->disableOriginalConstructor()
            ->getMock();

        $model_mock->expects($this->once())
            ->method('fill')
            ->with([
                'title' => 'title',
                'content' => 'content',
                'main_image' => 'saved_image1.png',
                'preview_image' => 'saved_image2.png',
            ]);

        $model_mock->expects($this->once())
            ->method('save')
            ->willReturn(false);

        $model_mock->title = 'title';
        $model_mock->content = 'content';
        $model_mock->main_image = 'saved_image1.png';
        $model_mock->preview_image = 'saved_image2.png';
        $model_mock->id = 1;

        $this->app->instance(
            Post::class,
            $model_mock
        );

        $this->withoutMiddleware(AuthWithToken::class)
            ->post(route('post.create'), $data)
            ->assertStatus(500);
    }
}
