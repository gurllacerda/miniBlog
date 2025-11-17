<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;


// Route::get('/', function(){
//     return view('welcome');
// });

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');


Route::middleware(['auth','verified'])->group(function () {
    Route::controller(PostController::class)->prefix('posts')->group(function () {
        Route::get('/', 'index')->name('posts');
        Route::get('/create', 'create')->name('posts.create');
        Route::post('/', 'store')->name('posts.store');
        Route::get('/{post}', 'show')->name('posts.show');
        Route::get('/{post}/edit', 'edit')
            ->name('posts.edit')
            ->can('update', 'post');
        Route::patch('/{post}', 'update')
            ->name('posts.update')
            ->can('update', 'post');
        Route::delete('/{post}', 'destroy')->name('posts.destroy');
        Route::get('/api/gerar-ideia', 'generateIdea')->name('posts.generate');
    });

    Route::controller(CommentController::class)->prefix('posts/{post}/comments')->group(function () {
        Route::post('/', 'store')->name('comments.store');
        Route::delete('/{comment}', 'destroy')->name('comments.destroy');
        Route::get('/{comment}/edit', 'edit')->name('comments.edit');
        Route::patch('/{comment}', 'update')->name('comments.update');
    });

    Route::controller(ProfileController::class)->prefix('profile')->group(function () {
        Route::get('/', 'edit')->name('profile.edit');
        Route::patch('/', 'update')->name('profile.update');
        Route::delete('/', 'destroy')->name('profile.destroy');
    });
});



require __DIR__.'/auth.php';



