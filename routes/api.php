<?php

use App\Http\Controllers\api\PhotoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\MessageController;

// ---WORK PHOTOS---

Route::get('/works/photos', [PhotoController::class, 'photosList'])->name('works.photos');
Route::get('/works/photos/next/prev/{work_id}', [PhotoController::class, 'photosNextAndPrevIds'])->name('works.photos.next/prev');
Route::get('/works/photos/{work_id}', [PhotoController::class, 'single'])->name('works.photos.single');

// !!!WORK PHOTOS!!!

// ---MESSAGES---

Route::post('/message/create', [MessageController::class, 'create'])->middleware(['throttle:1,1'])->name('message.create');

// !!!MESSAGES!!!
