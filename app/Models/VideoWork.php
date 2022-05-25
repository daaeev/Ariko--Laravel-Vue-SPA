<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoWork extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'videos_works';
    protected $guarded = [];
}
