<?php

namespace Tests\Feature\Services\ImageProcessing;

use App\Services\ImageProcessing\FileNameGenerators\FileNameGenerator;
use App\Services\ImageProcessing\ImageProcessing;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DefaultImageProcessingTest extends TestCase
{
    public function testSaveImageSuccess()
    {
        $image_dir = 'images';
        $disk = 'public';

        $instance = app(ImageProcessing::class);
        $instance->directory($image_dir);
        $instance->disk($disk);

        $generated_name = 'generated_name.png';

        $fnGen_mock = $this->getMockBuilder(FileNameGenerator::class)
            ->onlyMethods(['generate'])
            ->getMock();

        $image = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['storeAs'])
            ->getMock();

        $fnGen_mock->expects($this->once())
            ->method('generate')
            ->with($image)
            ->willReturn($generated_name);

        $image->expects($this->once())
            ->method('storeAs')
            ->with($image_dir, $generated_name, $disk)
            ->willReturn(true);

        $result = $instance->saveImage($image, $fnGen_mock);
        $this->assertEquals($generated_name, $result);
    }

    public function testSaveImageFailedNotSetDirectory()
    {
        $disk = 'public';

        $instance = app(ImageProcessing::class);
        $instance->disk($disk);

        $fnGen_mock = $this->getMockBuilder(FileNameGenerator::class)
            ->getMock();

        $image = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->expectException(\Exception::class);
        $instance->saveImage($image, $fnGen_mock);
    }

    public function testSaveImageFailedNotSetDisk()
    {
        $dir = 'images';

        $instance = app(ImageProcessing::class);
        $instance->directory($dir);

        $fnGen_mock = $this->getMockBuilder(FileNameGenerator::class)
            ->getMock();

        $image = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->expectException(\Exception::class);
        $instance->saveImage($image, $fnGen_mock);
    }

    public function testSaveImageFailedImageStoreFiled()
    {
        $image_dir = 'images';
        $disk = 'public';

        $instance = app(ImageProcessing::class);
        $instance->directory($image_dir);
        $instance->disk($disk);

        $generated_name = 'generated_name.png';

        $fnGen_mock = $this->getMockBuilder(FileNameGenerator::class)
            ->onlyMethods(['generate'])
            ->getMock();

        $image = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['storeAs'])
            ->getMock();

        $fnGen_mock->expects($this->once())
            ->method('generate')
            ->with($image)
            ->willReturn($generated_name);

        $image->expects($this->once())
            ->method('storeAs')
            ->with($image_dir, $generated_name, $disk)
            ->willReturn(false);

        $result = $instance->saveImage($image, $fnGen_mock);
        $this->assertFalse($result);
    }

    public function testDeleteImageIfExistsSuccess()
    {
        $image_dir = 'images';
        $disk = 'public';
        $image_name = 'image.png';

        $instance = app(ImageProcessing::class);
        $instance->directory($image_dir);
        $instance->disk($disk);

        $filesystem_mock = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['exists', 'delete'])
            ->getMock();

        $filesystem_mock->expects($this->once())
            ->method('exists')
            ->with($image_dir . '/' . $image_name)
            ->willReturn(true);

        $filesystem_mock->expects($this->once())
            ->method('delete')
            ->with($image_dir . '/' . $image_name)
            ->willReturn(true);

        Storage::shouldReceive('disk')
            ->twice()
            ->with($disk)
            ->andReturn($filesystem_mock);

        $result = $instance->deleteImage($image_name);
        $this->assertTrue($result);
    }

    public function testDeleteImageFailedNotSetDirectory()
    {
        $disk = 'public';
        $image = 'image.png';

        $instance = app(ImageProcessing::class);
        $instance->disk($disk);

        $this->expectException(\Exception::class);
        $instance->deleteImage($image);
    }

    public function testDeleteImageFailedNotSetDisk()
    {
        $dir = 'images';
        $image = 'image.png';

        $instance = app(ImageProcessing::class);
        $instance->directory($dir);

        $this->expectException(\Exception::class);
        $instance->deleteImage($image);
    }

    public function testDeleteImageIfNotExistsSuccess()
    {
        $image_dir = 'images';
        $disk = 'public';
        $image_name = 'image.png';

        $instance = app(ImageProcessing::class);
        $instance->directory($image_dir);
        $instance->disk($disk);

        $filesystem_mock = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['exists'])
            ->getMock();

        $filesystem_mock->expects($this->once())
            ->method('exists')
            ->with($image_dir . '/' . $image_name)
            ->willReturn(false);

        Storage::shouldReceive('disk')
            ->once()
            ->with($disk)
            ->andReturn($filesystem_mock);

        $result = $instance->deleteImage($image_name);
        $this->assertTrue($result);
    }

    public function testDeleteImageEmptyImageNameFailed()
    {
        $image_dir = 'images';
        $disk = 'public';
        $image_name = '';

        $instance = app(ImageProcessing::class);
        $instance->directory($image_dir);
        $instance->disk($disk);

        $result = $instance->deleteImage($image_name);
        $this->assertFalse($result);
    }

    public function testDeleteImageIfExistsFailed()
    {
        $image_dir = 'images';
        $disk = 'public';
        $image_name = 'image.png';

        $instance = app(ImageProcessing::class);
        $instance->directory($image_dir);
        $instance->disk($disk);

        $filesystem_mock = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['exists', 'delete'])
            ->getMock();

        $filesystem_mock->expects($this->once())
            ->method('exists')
            ->with($image_dir . '/' . $image_name)
            ->willReturn(true);

        $filesystem_mock->expects($this->once())
            ->method('delete')
            ->with($image_dir . '/' . $image_name)
            ->willReturn(false);

        Storage::shouldReceive('disk')
            ->twice()
            ->with($disk)
            ->andReturn($filesystem_mock);

        $result = $instance->deleteImage($image_name);
        $this->assertFalse($result);
    }
}
