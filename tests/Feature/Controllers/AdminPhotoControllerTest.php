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
use Illuminate\Support\Facades\Route;
use stdClass;
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
            ->onlyMethods(['disk', 'directory', 'saveFile'])
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
            ->method('saveFile')
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
            ->onlyMethods(['disk', 'directory', 'saveFile', 'deleteFile'])
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
        ->method('saveFile')
        ->withConsecutive([$image, $fnGen_mock], [$image, $fnGen_mock])
        ->willReturnOnConsecutiveCalls('saved_image1.png', false);

        $imgProc_mock->expects($this->once())
            ->method('deleteFile')
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
            ->onlyMethods(['disk', 'directory', 'saveFile'])
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
            ->method('saveFile')
            ->withConsecutive([$image, $fnGen_mock], [$image, $fnGen_mock])
            ->willReturnOnConsecutiveCalls('saved_image1.png', 'saved_image2.png');

        $model_mock = $this->getMockBuilder(Image::class)
            ->onlyMethods(['save', 'fill', 'delete'])
            ->disableOriginalConstructor()
            ->getMock();

        $model_mock->expects($this->exactly(2))
            ->method('save')
            ->willReturnOnConsecutiveCalls(true, false);

        $model_mock->expects($this->exactly(2))
            ->method('fill')
            ->withConsecutive(
                [[
                    'image' => 'saved_image1.png',
                    'photo_work_id' => $data['work_id']
                ]],
                [[
                    'image' => 'saved_image2.png',
                    'photo_work_id' => $data['work_id']
                ]],
            )
            ->willReturnSelf();

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

    public function testDeleteWorkSuccess()
    {
        $model_mock = $this->getMockBuilder(PhotoWork::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['delete'])
            ->getMock();
        
        $model_mock->expects($this->once())
            ->method('delete')
            ->willReturn(true);

        $image1 = new stdClass;
        $image1->image = 'image1.png';
        $image2 = new stdClass;
        $image2->image = 'image2.png';
        $image3 = new stdClass;
        $image3->image = 'image3.png';

        $model_mock->images = [
            $image1,
            $image2,
            $image3,
        ];
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
            ->with('photos')
            ->willReturnSelf();

        $fp_mock->expects($this->exactly(3))
            ->method('deleteFile')
            ->withConsecutive(['image1.png'], ['image2.png'], ['image3.png'])
            ->willReturnOnConsecutiveCalls(true, true, true);

        $this->instance(
            FileProcessingInterface::class,
            $fp_mock
        );

        $this->withoutMiddleware(AuthWithToken::class)
            ->delete(route('works.photos.delete', ['model' => $model_mock->id]))
            ->assertOk()
            ->assertJson(['id' => $model_mock->id]);
    }

    public function testDeleteWorkImageDeleteSuccess()
    {
        $model_mock = $this->getMockBuilder(PhotoWork::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();

        $image1 = new stdClass;
        $image1->image = 'image1.png';
        $image2 = new stdClass;
        $image2->image = 'image2.png';
        $image3 = new stdClass;
        $image3->image = 'image3.png';

        $model_mock->images = [
            $image1,
            $image2,
            $image3,
        ];
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
            ->with('photos')
            ->willReturnSelf();

        $fp_mock->expects($this->exactly(2))
            ->method('deleteFile')
            ->withConsecutive(['image1.png'], ['image2.png'])
            ->willReturnOnConsecutiveCalls(true, false);

        $this->instance(
            FileProcessingInterface::class,
            $fp_mock
        );

        $this->withoutMiddleware(AuthWithToken::class)
            ->delete(route('works.photos.delete', ['model' => $model_mock->id]))
            ->assertStatus(500);
    }

    public function testDeleteWorkModelDeleteFailed()
    {
        $model_mock = $this->getMockBuilder(PhotoWork::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['delete'])
            ->getMock();
        
        $model_mock->expects($this->once())
            ->method('delete')
            ->willReturn(false);

        $image1 = new stdClass;
        $image1->image = 'image1.png';
        $image2 = new stdClass;
        $image2->image = 'image2.png';
        $image3 = new stdClass;
        $image3->image = 'image3.png';

        $model_mock->images = [
            $image1,
            $image2,
            $image3,
        ];
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
            ->with('photos')
            ->willReturnSelf();

        $fp_mock->expects($this->exactly(3))
            ->method('deleteFile')
            ->withConsecutive(['image1.png'], ['image2.png'], ['image3.png'])
            ->willReturnOnConsecutiveCalls(true, true, true);

        $this->instance(
            FileProcessingInterface::class,
            $fp_mock
        );

        $this->withoutMiddleware(AuthWithToken::class)
            ->delete(route('works.photos.delete', ['model' => $model_mock->id]))
            ->assertStatus(500);
    }
}
