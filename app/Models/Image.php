<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['*'];

    public function work()
    {
        return $this->belongsTo(PhotoWork::class, 'photo_work_id');
    }
}
