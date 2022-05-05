<?php

use App\Http\Controllers\api\PhotoController;
use Illuminate\Support\Facades\Route;

Route::get('/works/photos', [PhotoController::class, 'photosList']);
