<?php

use \App\Http\Controllers;
use \App\Http\Controllers\NewsController;
use \App\Http\Controllers\ResourcesController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Navbar
Route::view('/home', 'home')->name('home');
Route::view('/bible', 'bible')->name('bible');
Route::prefix('news')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('news.index');
    Route::get('/create', [NewsController::class, 'create'])->name('news.create');
    Route::post('/', [NewsController::class, 'store'])->name('news.store');
    Route::get('/{id}/edit', [NewsController::class, 'edit'])->name('news.edit');
    Route::put('/{id}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/{id}', [NewsController::class, 'destroy'])->name('news.destroy');
});
Route::prefix('resources')->group(function () {
    Route::get('/', [ResourcesController::class, 'index'])->name('resources.index');
    Route::get('/create', [ResourcesController::class, 'create'])->name('resources.create');
    Route::post('/', [ResourcesController::class, 'store'])->name('resources.store');
    Route::get('/{id}/edit', [ResourcesController::class, 'edit'])->name('resources.edit');
    Route::put('/{id}', [ResourcesController::class, 'update'])->name('resources.update');
    Route::delete('/{id}', [ResourcesController::class, 'destroy'])->name('resources.destroy');
});
Route::view('/faq', 'faq')->name('faq');
Route::view('/contact', 'contact')->name('contact');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
