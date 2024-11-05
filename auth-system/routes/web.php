<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ImageController;

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/article/{article:slug}', [PublicController::class, 'show'])->name('article.show');
Route::get('/category/{category:slug}', [PublicController::class, 'category'])->name('category.show');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('articles', ArticleController::class);
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::resource('categories', CategoryController::class);
    Route::post('/upload-image', [ImageController::class, 'upload'])->middleware('auth');
});