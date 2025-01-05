<?php

use App\Http\Controllers\Dashboard\DatabaseController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;

Route::get('/initialize', [DatabaseController::class, 'index']);

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/dashboard')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::prefix('resources')->group(function(){
            Route::get('/databases', \App\Listings\Databases::class)->name('dashboard.databases.index');

            Route::post('/database', [DatabaseController::class, 'store'])->name('dashboard.databases.store');

            Route::delete('/database/{id}', [DatabaseController::class, 'destroy'])->name('dashboard.databases.destroy');
        });

        Route::get('/{page?}', function () {
            return view('dashboard');
        })->name('dashboard')
            ->where('page', '.*');
    });


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

