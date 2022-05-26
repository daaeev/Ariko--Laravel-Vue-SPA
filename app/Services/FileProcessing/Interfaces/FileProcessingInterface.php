<?php

namespace App\Services\FileProcessing\Interfaces;

use App\Services\FileProcessing\FileNameGenerators\Interfaces\FileNameGeneratorInterface;
use Illuminate\Http\UploadedFile;

interface FileProcessingInterface
{
    /**
     * Сохранение переданного файла
     *
     * @param UploadedFile $image
     * @param FileNameGeneratorInterface $fnGen
     * @return string|false имя изображения или false, при ошибке
     */
    public function saveFile(UploadedFile $image, FileNameGeneratorInterface $fnGen): string|false;

    /**
     * Удаление файла с переданным именем
     *
     * @param string $image_name
     * @return bool
     */
    public function deleteFile(string $image_name): bool;

    /**
     * Метод устанавливает хранилище для сохраняемого файла
     *
     * @param string $disk название хранилища
     * @return self
     */
    public function disk(string $disk): self;

    /**
     * Метод устанавливает директорию относительно директории хранилища,
     * в которую будет сохранен файл
     *
     * @param string $dir директория относительно директории хранилища
     * @return self
     */
    public function directory(string $dir): self;
}
