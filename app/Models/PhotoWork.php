<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoWork extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'photos_works';
    public $fillable = ['*'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(Image::class, 'photo_work_id');
    }
}
