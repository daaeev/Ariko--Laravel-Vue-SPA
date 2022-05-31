<?php

use App\Http\Controllers\api\admin\UserController;
use App\Http\Controllers\api\auth\AuthController;
use App\Http\Controllers\api\CommentController;
use App\Http\Controllers\api\PhotoController;
use App\Http\Controllers\api\VideoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\MessageController;
use App\Http\Controllers\api\PostController;
use App\Http\Controllers\api\admin\PhotoWorkController as AdminPhotoController;
use App\Http\Controllers\api\admin\PostController as AdminPostController;
use App\Http\Controllers\api\admin\VideoWorkController as AdminVideoWorkController;
use App\Http\Controllers\api\admin\MessageController as AdminMessageController;

// ---WORKS---

Route::prefix('works')->group(function () {
    Route::prefix('photos')->group(function () {
        Route::get('/', [PhotoController::class, 'photosList'])->name('works.photos');
        Route::get('/next/prev/{work_id}', [PhotoController::class, 'photosNextAndPrevIds'])->name('works.photos.next/prev');
        Route::get('/{work_id}', [PhotoController::class, 'single'])->name('works.photos.single');
    });

    Route::prefix('videos')->group(function () {
        Route::get('/', [VideoController::class, 'videosList'])->name('works.videos');
        Route::get('/next/prev/{work_id}', [VideoController::class, 'videosNextAndPrevIds'])->name('works.videos.next/prev');
        Route::get('/{work_id}', [VideoController::class, 'single'])->name('works.videos.single');
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
        Route::delete('/user/{model}', [UserController::class, 'deleteUser'])->name('user.delete');

    // !!!USER!!!

    // ---PHOTOS WORK---

        Route::post('/works/photos', [AdminPhotoController::class, 'createWork'])->name('works.photos.create');
        Route::post('/works/photos/images', [AdminPhotoController::class, 'addImagesToWork'])->name('works.photos.images.add');
        Route::delete('/works/photos/{model}', [AdminPhotoController::class, 'deleteWork'])->name('works.photos.delete');

    // !!!PHOTOS WORK!!!

    // ---POST---

        Route::post('/post', [AdminPostController::class, 'createPost'])->name('post.create');
        Route::delete('/post/{model}', [AdminPostController::class, 'deletePost'])->name('post.delete');

    // !!!POST!!!

    // ---VIDEOS WORK---

        Route::post('/works/video', [AdminVideoWorkController::class, 'createWork'])->name('works.videos.create');
        Route::delete('/works/videos/{model}', [AdminVideoWorkController::class, 'deleteWork'])->name('works.videos.delete');

    // !!!VIDEOS WORK!!!

    // ---MESSAGES---

        Route::get('/contact/messages', [AdminMessageController::class, 'messagesList'])->name('messages.list');
        Route::delete('/contact/message/{model}', [AdminMessageController::class, 'deleteMessage'])->name('messages.delete');

    // !!!MESSAGES!!!
});

// !!!ADMIN ROUTES!!!
