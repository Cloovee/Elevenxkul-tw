<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashAdminController;
use App\Http\Controllers\Admin\EkskulController;

Route::get('/', function () {
    return redirect('/admin/dashboard');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashAdminController::class, 'index'])->name('dashboard');
    Route::resource('ekskul', EkskulController::class);
});

// Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Route::get('/dashboard', [DashAdminController::class, 'index'])->name('dashboard');
    // Route::resource('ekskul', EkskulController::class);
// });