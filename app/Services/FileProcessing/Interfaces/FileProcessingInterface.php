<?php

namespace App\Services\FileProcessing\Interfaces;

use App\Services\FileProcessing\FileNameGenerators\Interfaces\FileNameGeneratorInterface;
use Illuminate\Http\UploadedFile;

interface FileProcessingInterface
{
    /**
     * Сохранение переданного файла-изображения
     *
     * @param UploadedFile $image
     * @param FileNameGeneratorInterface $fnGen
     * @return string|false имя изображения или false, при ошибке
     */
    public function saveImage(UploadedFile $image, FileNameGeneratorInterface $fnGen): string|false;

    /**
     * Удаление файла-изображения с переданным именем
     *
     * @param string $image_name
     * @return bool
     */
    public function deleteImage(string $image_name): bool;

    /**
     * Метод устанавливает хранилище для сохраняемого изображения
     *
     * @param string $disk название хранилища
     * @return self
     */
    public function disk(string $disk): self;

    /**
     * Метод устанавливает директорию относительно директории хранилища,
     * в которую будет сохранено изображение
     *
     * @param string $dir директория относительно директории хранилища
     * @return self
     */
    public function directory(string $dir): self;
}
