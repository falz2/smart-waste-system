<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BinController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TruckController;
use App\Http\Controllers\CollectionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/map', [DashboardController::class, 'map'])->name('map');

    Route::resource('bins', BinController::class);
    Route::resource('reports', ReportController::class)->only(['index', 'show', 'destroy']);
    Route::patch('reports/{report}/status', [ReportController::class, 'updateStatus'])->name('reports.updateStatus');
    Route::resource('trucks', TruckController::class);
    Route::resource('collections', CollectionController::class);
    Route::patch('collections/{collection}/complete', [CollectionController::class, 'complete'])->name('collections.complete');
});