<?php

use App\Http\Controllers\Reports\ActivityLogController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/log', [ActivityLogController::class, 'index'])->name('activity.index');
        Route::get('/log/{activity}', [ActivityLogController::class, 'view'])->name('activity.view');
    });
});
