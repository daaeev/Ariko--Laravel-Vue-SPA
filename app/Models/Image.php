<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $timestamps = false;
    protected $guarded = ['*'];

    public function work()
    {
        return $this->belongsTo(PhotoWork::class, 'photo_work_id');
    }
}
