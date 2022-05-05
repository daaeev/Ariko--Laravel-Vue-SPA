<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoWork extends Model
{
    public $timestamps = false;
    public $table = 'photos_works';
    protected $guarded  = ['preview_image_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(Image::class, 'photo_work_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function preview() {
        return $this->belongsTo(Image::class, 'preview_image_id');
    }
}
