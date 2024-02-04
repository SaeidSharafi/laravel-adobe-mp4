<?php

use App\Http\Controllers\Settings\AdobeServerSettingController;

Route::middleware(['auth'])->group(function () {
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/adobe-server', [AdobeServerSettingController::class, 'edit'])->name('adobe-server.edit');
        Route::post('/adobe-server', [AdobeServerSettingController::class, 'store'])->name('adobe-server.store');
    });
});
