<?php

namespace Tests\Unit\Services\FileNameGenerator;

use App\Services\FileProcessing\FileNameGenerators\FileNameGenerator;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DefaultFileNameGeneratorTest extends TestCase
{
    public function testCheckGeneratedName()
    {
        $image = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getClientOriginalExtension'])
            ->getMock();

        $image->expects($this->once())
            ->method('getClientOriginalExtension')
            ->willReturn('png');

        $instance = app(FileNameGenerator::class);
        $name = $instance->generate($image);

        $this->assertTrue(mb_strlen($name) === 64);
        $exploded = explode('.', $name);
        $this->assertEquals('png', $exploded[1]);
    }
}
