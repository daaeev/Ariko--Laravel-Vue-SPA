<?php

use App\Http\Controllers\api\PhotoController;
use Illuminate\Support\Facades\Route;

// ---WORK PHOTOS---

Route::get('/works/photos', [PhotoController::class, 'photosList']);
Route::get('/works/photos/next/prev/{work_id}', [PhotoController::class, 'photosNextAndPrevIds']);
Route::get('/works/photos/{work_id}', [PhotoController::class, 'single']);
Route::get('/works/photos/images/{work_id}', [PhotoController::class, 'singleImages']);

// !!!WORK PHOTOS!!!
