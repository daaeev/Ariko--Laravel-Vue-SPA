<?php

namespace App\Services\ImageProcessing;

use App\Services\ImageProcessing\Interfaces\ImageProcessingInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageProcessing implements ImageProcessingInterface
{
    /**
     * @var string идентификатор файлового хранилища
     */
    protected string $storage_disk;

    /**
     * @var string директория относительно файлового хранилища для хранения изображений
     */
    protected string $image_store_dir;

    /**
     * @inheritDoc
     */
    public function saveImage(UploadedFile $image): string|false
    {
        $image_name = Str::random(60) . '.' . $image->getClientOriginalExtension();

        if ($image->storeAs($this->image_store_dir, $image_name, $this->storage_disk)) {
            return $image_name;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function deleteImage(string $image_name): bool
    {
        if (empty($image_name)) {
            return false;
        }

        if (!Storage::disk($this->storage_disk)->exists($this->image_store_dir . '/' . $image_name)) {
            return true;
        }

        if (Storage::disk($this->storage_disk)->delete($this->image_store_dir . '/' . $image_name)) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function directory(string $dir): self
    {
        $this->image_store_dir = $dir;

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
