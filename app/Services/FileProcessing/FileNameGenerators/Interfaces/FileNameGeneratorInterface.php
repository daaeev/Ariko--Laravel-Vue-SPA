<?php

namespace App\Services\FileProcessing\FileNameGenerators\Interfaces;

use Illuminate\Http\UploadedFile;

interface FileNameGeneratorInterface
{
    /**
     * Генерация случайного имени для файла
     *
     * @param UploadedFile $file
     * @return string
     */
    public function generate(UploadedFile $file): string;
}
