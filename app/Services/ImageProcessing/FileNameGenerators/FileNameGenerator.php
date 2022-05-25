<?php

namespace App\Services\ImageProcessing\FileNameGenerators;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileNameGenerator implements Interfaces\FileNameGeneratorInterface
{
    /**
     * @inheritDoc
     */
    public function generate(UploadedFile $file): string
    {
        return Str::random(60) . '.' . $file->getClientOriginalExtension();
    }
}
