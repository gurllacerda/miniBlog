<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController as ApiPostController;
use App\Http\Controllers\Api\CommentController as ApiCommentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('api.login');
Route::post('/register', [RegisteredUserController::class, 'create'])->name('register');

Route::get('/posts', [ApiPostController::class, 'index']);
Route::get('/posts/{post}', [ApiPostController::class, 'show'])->middleware('auth:sanctum');
Route::post('/posts', [ApiPostController::class, 'store'])->middleware('auth:sanctum');
Route::delete('/posts/{post}', [ApiPostController::class, 'destroy'])->middleware('auth:sanctum'); //with {post} parameter you can access the id in the controller method
Route::patch('/posts/{post}', [ApiPostController::class, 'update'])->middleware('auth:sanctum');


Route::controller(ApiCommentController::class)
        ->prefix('posts/{post}/comments')
        ->middleware('auth:sanctum')
        ->group(function () {
            Route::get('/', 'index')->name('comments.api');
            Route::post('/', 'store')->name('comments.api.store');
            Route::get('/{comment}', 'show')->name('comments.api.show');
            Route::delete('/{comment}', 'destroy')->name('comments.api.destroy');
            Route::patch('/{comment}', 'update')->name('comments.api.update');
        });