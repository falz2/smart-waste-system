<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BinController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TruckController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\IotController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__.'/auth.php';

Route::post('/api/iot/update', [IotController::class, 'update'])->name('iot.update');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/map', [DashboardController::class, 'map'])->name('map');

    Route::resource('reports', ReportController::class)->only(['index', 'create', 'store', 'show']);

    Route::middleware('role:admin,collector')->group(function () {
        Route::get('collections', [CollectionController::class, 'index'])->name('collections.index');
        Route::get('collections/{collection}', [CollectionController::class, 'show'])->whereNumber('collection')->name('collections.show');
        Route::patch('collections/{collection}/complete', [CollectionController::class, 'complete'])->name('collections.complete');
    });

    Route::middleware('role:admin')->group(function () {
        Route::resource('bins', BinController::class);
        Route::resource('trucks', TruckController::class);
        Route::get('collections/create', [CollectionController::class, 'create'])->name('collections.create');
        Route::post('collections', [CollectionController::class, 'store'])->name('collections.store');
        Route::delete('collections/{collection}', [CollectionController::class, 'destroy'])->name('collections.destroy');
        Route::patch('reports/{report}/status', [ReportController::class, 'updateStatus'])->name('reports.updateStatus');
        Route::delete('reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
        Route::get('/iot/simulate', [IotController::class, 'simulate'])->name('iot.simulate');
        Route::post('/iot/tick', [IotController::class, 'tick'])->name('iot.tick');
        Route::post('/iot/reset', [IotController::class, 'reset'])->name('iot.reset');
    });
});