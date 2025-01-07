<?php

use App\Http\Controllers\Dashboard\DatabaseController;
use Illuminate\Support\Facades\Route;

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
