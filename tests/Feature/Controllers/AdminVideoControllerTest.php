<?php

namespace Tests\Feature\Controllers;

use App\Http\Middleware\AuthWithToken;
use App\Http\Requests\CreateVideoWork;
use App\Models\VideoWork;
use App\Services\FileProcessing\FileNameGenerators\FileNameGenerator;
use App\Services\FileProcessing\FileNameGenerators\Interfaces\FileNameGeneratorInterface;
use App\Services\FileProcessing\FileProcessing;
use App\Services\FileProcessing\Interfaces\FileProcessingInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminVideoControllerTest extends TestCase
{
    public function testCreateWorkSuccess()
    {
        $video = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $data = [
            'name' => 'name',
            'subject' => 'subject',
            'year' => 2022,
            'video' => $video,
        ];

        $request_mock = $this->getMockBuilder(CreateVideoWork::class)
            ->onlyMethods(['validated'])
            ->disableOriginalConstructor()
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($data);

        $this->instance(
            CreateVideoWork::class,
            $request_mock
        );

        $fnGen_mock = $this->getMockBuilder(FileNameGenerator::class)
            ->getMock();

        $this->instance(
            FileNameGeneratorInterface::class,
            $fnGen_mock
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
            ->with('videos')
            ->willReturnSelf();

        $fp_mock->expects($this->once())
            ->method('saveFile')
            ->with($video, $fnGen_mock)
            ->willReturn('saved_video.mp4');

        $this->instance(
            FileProcessingInterface::class,
            $fp_mock
        );

        $model_mock = $this->getMockBuilder(VideoWork::class)
            ->onlyMethods(['fill', 'save'])
            ->disableOriginalConstructor()
            ->getMock();

        $model_mock->expects($this->once())
            ->method('fill')
            ->with([
                'name' => 'name',
                'subject' => 'subject',
                'year' => 2022,
                'video' => 'saved_video.mp4',
            ]);

        $model_mock->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $model_mock->name = 'name';
        $model_mock->subject = 'subject';
        $model_mock->year = 2022;
        $model_mock->video = 'saved_video.mp4';
        $model_mock->id = 1;

        $this->instance(
            VideoWork::class,
            $model_mock
        );

        $this->withoutMiddleware(AuthWithToken::class)
            ->post(route('works.videos.create'), $data)
            ->assertOk()
            ->assertJson([
                'name' => 'name',
                'subject' => 'subject',
                'year' => 2022,
                'video' => 'saved_video.mp4',
                'id' => 1
            ]);
    }

    public function testCreateWorkFileSaveFailed()
    {
        $video = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $data = [
            'name' => 'name',
            'subject' => 'subject',
            'year' => 2022,
            'video' => $video,
        ];

        $request_mock = $this->getMockBuilder(CreateVideoWork::class)
            ->onlyMethods(['validated'])
            ->disableOriginalConstructor()
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($data);

        $this->instance(
            CreateVideoWork::class,
            $request_mock
        );

        $fnGen_mock = $this->getMockBuilder(FileNameGenerator::class)
            ->getMock();

        $this->instance(
            FileNameGeneratorInterface::class,
            $fnGen_mock
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
            ->with('videos')
            ->willReturnSelf();

        $fp_mock->expects($this->once())
            ->method('saveFile')
            ->with($video, $fnGen_mock)
            ->willReturn(false);

        $this->instance(
            FileProcessingInterface::class,
            $fp_mock
        );

        $this->withoutMiddleware(AuthWithToken::class)
            ->post(route('works.videos.create'), $data)
            ->assertStatus(500);
    }

    public function testCreateWorkModelSaveFailed()
    {
        $video = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $data = [
            'name' => 'name',
            'subject' => 'subject',
            'year' => 2022,
            'video' => $video,
        ];

        $request_mock = $this->getMockBuilder(CreateVideoWork::class)
            ->onlyMethods(['validated'])
            ->disableOriginalConstructor()
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($data);

        $this->instance(
            CreateVideoWork::class,
            $request_mock
        );

        $fnGen_mock = $this->getMockBuilder(FileNameGenerator::class)
            ->getMock();

        $this->instance(
            FileNameGeneratorInterface::class,
            $fnGen_mock
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
            ->with('videos')
            ->willReturnSelf();

        $fp_mock->expects($this->once())
            ->method('saveFile')
            ->with($video, $fnGen_mock)
            ->willReturn('saved_video.mp4');

        $fp_mock->expects($this->once())
            ->method('deleteFile')
            ->with('saved_video.mp4');

        $this->instance(
            FileProcessingInterface::class,
            $fp_mock
        );

        $model_mock = $this->getMockBuilder(VideoWork::class)
            ->onlyMethods(['fill', 'save'])
            ->disableOriginalConstructor()
            ->getMock();

        $model_mock->expects($this->once())
            ->method('fill')
            ->with([
                'name' => 'name',
                'subject' => 'subject',
                'year' => 2022,
                'video' => 'saved_video.mp4',
            ]);

        $model_mock->expects($this->once())
            ->method('save')
            ->willReturn(false);

        $this->instance(
            VideoWork::class,
            $model_mock
        );

        $this->withoutMiddleware(AuthWithToken::class)
            ->post(route('works.videos.create'), $data)
            ->assertStatus(500);
    }

    public function testDeleteWorkSuccess()
    {
        $model_mock = $this->getMockBuilder(VideoWork::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['delete'])
            ->getMock();

        $model_mock->expects($this->once())
            ->method('delete')
            ->willReturn(true);

        $model_mock->video = 'video.mp4';
        $model_mock->id = 1;

        Route::bind('model', function ($value) use ($model_mock) {
            return $model_mock;
        });

        $fp_mock = $this->getMockBuilder(FileProcessing::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['disk', 'directory', 'deleteFile'])
            ->getMock();
        
        $fp_mock->expects($this->once())
            ->method('disk')
            ->with('public')
            ->willReturnSelf();

        $fp_mock->expects($this->once())
            ->method('directory')
            ->with('videos')
            ->willReturnSelf();

        $fp_mock->expects($this->once())
            ->method('deleteFile')
            ->with($model_mock->video)
            ->willReturn(true);

        $this->instance(FileProcessingInterface::class, $fp_mock);

        $this->withoutMiddleware(AuthWithToken::class)
            ->delete(route('works.videos.delete', ['model' => $model_mock->id]))
            ->assertOk()
            ->assertJson(['id' => $model_mock->id]);
    }

    public function testDeleteWorkVideoDeleteFailed()
    {
        $model_mock = $this->getMockBuilder(VideoWork::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();

        $model_mock->video = 'video.mp4';
        $model_mock->id = 1;

        Route::bind('model', function ($value) use ($model_mock) {
            return $model_mock;
        });

        $fp_mock = $this->getMockBuilder(FileProcessing::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['disk', 'directory', 'deleteFile'])
            ->getMock();
        
        $fp_mock->expects($this->once())
            ->method('disk')
            ->with('public')
            ->willReturnSelf();

        $fp_mock->expects($this->once())
            ->method('directory')
            ->with('videos')
            ->willReturnSelf();

        $fp_mock->expects($this->once())
            ->method('deleteFile')
            ->with($model_mock->video)
            ->willReturn(false);

        $this->instance(FileProcessingInterface::class, $fp_mock);

        $this->withoutMiddleware(AuthWithToken::class)
            ->delete(route('works.videos.delete', ['model' => $model_mock->id]))
            ->assertStatus(500);
    }

    public function testDeleteWorkModelDeleteFailed()
    {
        $model_mock = $this->getMockBuilder(VideoWork::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['delete'])
            ->getMock();

        $model_mock->expects($this->once())
            ->method('delete')
            ->willReturn(false);

        $model_mock->video = 'video.mp4';
        $model_mock->id = 1;

        Route::bind('model', function ($value) use ($model_mock) {
            return $model_mock;
        });

        $fp_mock = $this->getMockBuilder(FileProcessing::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['disk', 'directory', 'deleteFile'])
            ->getMock();
        
        $fp_mock->expects($this->once())
            ->method('disk')
            ->with('public')
            ->willReturnSelf();

        $fp_mock->expects($this->once())
            ->method('directory')
            ->with('videos')
            ->willReturnSelf();

        $fp_mock->expects($this->once())
            ->method('deleteFile')
            ->with($model_mock->video)
            ->willReturn(true);

        $this->instance(FileProcessingInterface::class, $fp_mock);

        $this->withoutMiddleware(AuthWithToken::class)
            ->delete(route('works.videos.delete', ['model' => $model_mock->id]))
            ->assertStatus(500);
    }
}
