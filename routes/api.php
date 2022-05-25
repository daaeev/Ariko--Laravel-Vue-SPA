<?php

use App\Http\Controllers\api\admin\UserController;
use App\Http\Controllers\api\auth\AuthController;
use App\Http\Controllers\api\CommentController;
use App\Http\Controllers\api\PhotoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\MessageController;
use App\Http\Controllers\api\PostController;
use App\Http\Controllers\api\admin\PhotoWorkController as AdminPhotoController;

// ---WORKS---

Route::prefix('works')->group(function () {
    Route::prefix('photos')->group(function () {
        Route::get('/', [PhotoController::class, 'photosList'])->name('works.photos');
        Route::get('/next/prev/{work_id}', [PhotoController::class, 'photosNextAndPrevIds'])->name('works.photos.next/prev');
        Route::get('/{work_id}', [PhotoController::class, 'single'])->name('works.photos.single');
    });
});

// !!!WORKS!!!

// ---MESSAGES---

Route::post('/message', [MessageController::class, 'create'])->middleware(['throttle:1,5'])->name('message.create');

// !!!MESSAGES!!!

// ---POSTS---

Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'postsList'])->name('posts.all');
    Route::get('/{post_id}', [PostController::class, 'single'])->name('posts.single');
    Route::get('/tag/{tag}', [PostController::class, 'postByTag'])->name('posts.by-tag');
});

// !!!POSTS!!!

// ---COMMENTS---

Route::prefix('comments')->group(function () {
    Route::post('/', [CommentController::class, 'createComment'])->middleware('throttle:1,5')->name('comment.create');
    Route::get('/{post_id}', [CommentController::class, 'commentsByPost'])->name('comments.by-post');
});

// !!!COMMENTS!!!

// ---AUTH---

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:1,0.5')->name('auth.login');
    Route::post('/check', [AuthController::class, 'authCheck'])->name('auth.check');
});

// !!!AUTH!!!

// ---ADMIN ROUTES---

Route::middleware('auth')->group(function () {

    // ---USER---

    Route::post('/user', [UserController::class, 'create'])->name('user.create');

    // !!!USER!!!

    // ---PHOTOS WORK---

    Route::post('/works/photos', [AdminPhotoController::class, 'createWork'])->name('works.photos.create');
    Route::post('/works/photos/images', [AdminPhotoController::class, 'addImagesToWork'])->name('works.photos.images.add');

    // !!!PHOTOS WORK!!!

});

// !!!ADMIN ROUTES!!!
