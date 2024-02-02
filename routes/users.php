<?php

use App\Http\Controllers\Export\ExportUserController;
use App\Http\Controllers\Import\UserImportController;
use App\Http\Controllers\Users\Notification\NotificationMarkAllAsReadController;
use App\Http\Controllers\Users\Notification\NotificationMarkAsReadController;
use App\Http\Controllers\Users\ProfileController;
use App\Http\Controllers\Users\RoleController;
use App\Http\Controllers\Users\RolePermissionController;
use App\Http\Controllers\Users\UserContoller;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->group(function () {
    Route::prefix('users')->name('users.')->group(function () {
        Route::post('notification/mark-all-as-read', NotificationMarkAllAsReadController::class)
            ->name('notification.mark-all-as-read');
        Route::post('notification/{notification}', NotificationMarkAsReadController::class)
            ->name('notification.mark-as-read');


        Route::put('/user/restore/{user}', [UserContoller::class, 'restore'])
            ->withTrashed()
            ->name('user.restore');

        Route::get('/user/import', [UserImportController::class,'create'])->name('user.import.create');
        Route::post('/user/import', [UserImportController::class,'store'])->name('user.import.store');
        Route::get('/user/export', ExportUserController::class)
            ->name('user.export');

        Route::resource('/user', UserContoller::class);
        Route::delete('/user/{user}', [UserContoller::class, 'destroy'])
            ->withTrashed()
            ->name('user.destroy');
        Route::resource('/role', RoleController::class);

        Route::get('/role/{role}/permissions', [RolePermissionController::class, 'index'])
            ->name('permission-role.index');
        Route::post('/role/{role}/permissions', [RolePermissionController::class, 'update'])
            ->name('permission-role.update');
    });


    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.view');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::put('/profile/edit', [ProfileController::class, 'update'])
        ->name('profile.update');
});
