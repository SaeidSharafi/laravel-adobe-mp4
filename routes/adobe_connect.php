<?php

use App\Http\Controllers\AdobeConnect\AdobeConnectRecordingController;

Route::middleware(['auth'])->group(function () {
    Route::prefix('adobe-connect')->name('adobe-connect.')->group(function () {
        Route::resource('/recording', AdobeConnectRecordingController::class);
    });
});
