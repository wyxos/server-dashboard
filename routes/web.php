<?php

use App\Http\Controllers\Dashboard\DatabaseController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/initialize', [DatabaseController::class, 'index']);

Route::get('/', function () {
    return view('welcome');
});

require __DIR__ . '/dashboard.php';


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

