<?php

namespace Tests\Feature\Controllers;

use App\Http\Middleware\AuthWithToken;
use App\Http\Requests\AddImagesToWork;
use App\Http\Requests\CreatePhotoWork;
use App\Models\Image;
use App\Models\PhotoWork;
use App\Services\FileProcessing\FileNameGenerators\FileNameGenerator;
use App\Services\FileProcessing\FileNameGenerators\Interfaces\FileNameGeneratorInterface;
use App\Services\FileProcessing\FileProcessing;
use App\Services\FileProcessing\Interfaces\FileProcessingInterface;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminPhotoControllerTest extends TestCase
{
    public function testCreateWorkSuccess()
    {
        $data = [
            'name' => 'name',
            'subject' => 'subject',
            'year' => 2022,
        ];

        $model_mock = $this->getMockBuilder(PhotoWork::class)
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
        $model_mock->subject = $data['subject'];
        $model_mock->year = $data['year'];
        $model_mock->id = 1;

        $request_mock = $this->getMockBuilder(CreatePhotoWork::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($data);

        $this->app->instance(
            PhotoWork::class,
            $model_mock
        );

        $this->app->instance(
            CreatePhotoWork::class,
            $request_mock
        );

        $this->withoutMiddleware(AuthWithToken::class)
            ->post(route('works.photos.create'), $data)
            ->assertOk()
            ->assertJson([
                'name' => 'name',
                'subject' => 'subject',
                'year' => 2022,
                'id' => 1
            ]);
    }

    public function testCreateWorkModelSaveFailed()
    {
        $data = [
            'name' => 'name',
            'subject' => 'subject',
            'year' => 2022,
        ];

        $model_mock = $this->getMockBuilder(PhotoWork::class)
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
        $model_mock->subject = $data['subject'];
        $model_mock->year = $data['year'];
        $model_mock->id = 1;

        $request_mock = $this->getMockBuilder(CreatePhotoWork::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($data);

        $this->app->instance(
            CreatePhotoWork::class,
            $request_mock
        );

        $this->app->instance(
            PhotoWork::class,
            $model_mock
        );

        $this->withoutMiddleware(AuthWithToken::class)
            ->post(route('works.photos.create'), $data)
            ->assertStatus(500);
    }

    public function testAddImagesSuccess()
    {
        $image = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $data = [
            'images' => [
                $image, $image
            ],
            'work_id' => 1,
        ];

        $request_mock = $this->getMockBuilder(AddImagesToWork::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($data);

        $this->app->instance(
            AddImagesToWork::class,
            $request_mock
        );

        $imgProc_mock = $this->getMockBuilder(FileProcessing::class)
            ->onlyMethods(['disk', 'directory', 'saveImage'])
            ->getMock();

        $imgProc_mock->expects($this->once())
            ->method('disk')
            ->with('public')
            ->willReturnSelf();

        $imgProc_mock->expects($this->once())
            ->method('directory')
            ->with('photos')
            ->willReturnSelf();

        $this->app->instance(
            FileProcessingInterface::class,
            $imgProc_mock
        );

        $fnGen_mock = $this->getMockBuilder(FileNameGenerator::class)
            ->getMock();

        $this->app->instance(
            FileNameGeneratorInterface::class,
            $fnGen_mock
        );

        $imgProc_mock->expects($this->exactly(2))
            ->method('saveImage')
            ->with($image, $fnGen_mock)
            ->willReturn('saved_image.png');

        $model_mock = $this->getMockBuilder(Image::class)
            ->onlyMethods(['save', 'fill'])
            ->disableOriginalConstructor()
            ->getMock();

        $model_mock->expects($this->exactly(2))
            ->method('fill')
            ->with([
                'image' => 'saved_image.png',
                'photo_work_id' => $data['work_id']
            ])
            ->willReturnSelf();

        $model_mock->image = 'saved_image.png';
        $model_mock->photo_work_id = $data['work_id'];
        $model_mock->id = 1;

        $model_mock->expects($this->exactly(2))
            ->method('save')
            ->willReturn(true);

        $this->app->instance(
            Image::class,
            $model_mock
        );

        $this->withoutMiddleware(AuthWithToken::class)
            ->post(route('works.photos.images.add'), $data)
            ->assertOk()
            ->assertJson([
                [
                    'image' => 'saved_image.png',
                    'photo_work_id' => $data['work_id'],
                    'id' => 1
                ],
                [
                    'image' => 'saved_image.png',
                    'photo_work_id' => $data['work_id'],
                    'id' => 1
                ],
            ]);
    }

    public function testAddImagesFailedSecondImageSaveInLS()
    {
        $image = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $data = [
            'images' => [
                $image, $image
            ],
            'work_id' => 1,
        ];

        $request_mock = $this->getMockBuilder(AddImagesToWork::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($data);

        $this->app->instance(
            AddImagesToWork::class,
            $request_mock
        );

        $imgProc_mock = $this->getMockBuilder(FileProcessing::class)
            ->onlyMethods(['disk', 'directory', 'saveImage', 'deleteImage'])
            ->getMock();

        $imgProc_mock->expects($this->once())
            ->method('disk')
            ->with('public')
            ->willReturnSelf();

        $imgProc_mock->expects($this->once())
            ->method('directory')
            ->with('photos')
            ->willReturnSelf();

        $this->app->instance(
            FileProcessingInterface::class,
            $imgProc_mock
        );

        $fnGen_mock = $this->getMockBuilder(FileNameGenerator::class)
            ->getMock();

        $this->app->instance(
            FileNameGeneratorInterface::class,
            $fnGen_mock
        );

        $imgProc_mock->expects($this->at(2))
            ->method('saveImage')
            ->with($image, $fnGen_mock)
            ->willReturn('saved_image1.png');

        $imgProc_mock->expects($this->at(3))
            ->method('saveImage')
            ->with($image, $fnGen_mock)
            ->willReturn(false);

        $imgProc_mock->expects($this->once())
            ->method('deleteImage')
            ->with('saved_image1.png');

        $this->withoutMiddleware(AuthWithToken::class)
            ->post(route('works.photos.images.add'), $data)
            ->assertStatus(500);
    }

    public function testAddImagesFailedSecondModelSave()
    {
        $image = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $data = [
            'images' => [
                $image, $image
            ],
            'work_id' => 1,
        ];

        $request_mock = $this->getMockBuilder(AddImagesToWork::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($data);

        $this->app->instance(
            AddImagesToWork::class,
            $request_mock
        );

        $imgProc_mock = $this->getMockBuilder(FileProcessing::class)
            ->onlyMethods(['disk', 'directory', 'saveImage'])
            ->getMock();

        $imgProc_mock->expects($this->once())
            ->method('disk')
            ->with('public')
            ->willReturnSelf();

        $imgProc_mock->expects($this->once())
            ->method('directory')
            ->with('photos')
            ->willReturnSelf();

        $this->app->instance(
            FileProcessingInterface::class,
            $imgProc_mock
        );

        $fnGen_mock = $this->getMockBuilder(FileNameGenerator::class)
            ->getMock();

        $this->app->instance(
            FileNameGeneratorInterface::class,
            $fnGen_mock
        );

        $imgProc_mock->expects($this->at(2))
            ->method('saveImage')
            ->with($image, $fnGen_mock)
            ->willReturn('saved_image1.png');

        $imgProc_mock->expects($this->at(3))
            ->method('saveImage')
            ->with($image, $fnGen_mock)
            ->willReturn('saved_image2.png');

        $model_mock = $this->getMockBuilder(Image::class)
            ->onlyMethods(['save', 'fill', 'delete'])
            ->disableOriginalConstructor()
            ->getMock();

        $model_mock->expects($this->at(0))
            ->method('fill')
            ->with([
                'image' => 'saved_image1.png',
                'photo_work_id' => $data['work_id']
            ])
            ->willReturnSelf();

        $model_mock->expects($this->at(1))
            ->method('save')
            ->willReturn(true);

        $model_mock->expects($this->at(2))
            ->method('fill')
            ->with([
                'image' => 'saved_image2.png',
                'photo_work_id' => $data['work_id']
            ])
            ->willReturnSelf();

        $model_mock->expects($this->at(3))
            ->method('save')
            ->willReturn(false);

        $model_mock->expects($this->once())
            ->method('delete')
            ->willReturn(true);

        $this->app->instance(
            Image::class,
            $model_mock
        );

        $this->withoutMiddleware(AuthWithToken::class)
            ->post(route('works.photos.images.add'), $data)
            ->assertStatus(500);
    }
}
