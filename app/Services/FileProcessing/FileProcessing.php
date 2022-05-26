<?php

namespace App\Services\FileProcessing;

use App\Services\FileProcessing\FileNameGenerators\Interfaces\FileNameGeneratorInterface;
use App\Services\FileProcessing\Interfaces\FileProcessingInterface;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileProcessing implements FileProcessingInterface
{
    /**
     * @var string идентификатор файлового хранилища
     */
    protected string $storage_disk;

    /**
     * @var string директория относительно файлового хранилища для хранения файла
     */
    protected string $file_store_dir;

    /**
     * @inheritDoc
     */
    public function saveFile(UploadedFile $file, FileNameGeneratorInterface $fnGen): string|false
    {
        if (!isset($this->file_store_dir) || !isset($this->storage_disk)) {
            throw new Exception("Two methods 'directory' and 'disk' must be called");
        }

        $file_name = $fnGen->generate($file);

        if ($file->storeAs($this->file_store_dir, $file_name, $this->storage_disk)) {
            return $file_name;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function deleteFile(string $file_name): bool
    {
        if (!isset($this->file_store_dir) || !isset($this->storage_disk)) {
            throw new Exception("Two methods 'directory' and 'disk' must be called");
        }

        if (empty($file_name)) {
            return false;
        }

        if (!Storage::disk($this->storage_disk)->exists($this->file_store_dir . '/' . $file_name)) {
            return true;
        }

        if (Storage::disk($this->storage_disk)->delete($this->file_store_dir . '/' . $file_name)) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function directory(string $dir): self
    {
        $this->file_store_dir = $dir;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function disk(string $disk): self
    {
        $this->storage_disk = $disk;

        return $this;
    }
}
