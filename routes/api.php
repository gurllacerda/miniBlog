<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController as ApiPostController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('api.login');
Route::post('/register', [RegisteredUserController::class, 'create'])->name('register');

Route::get('/posts', [ApiPostController::class, 'index']);
Route::get('/posts/{post}', [ApiPostController::class, 'show']);
Route::post('/posts', [ApiPostController::class, 'store'])->middleware('auth:sanctum');
