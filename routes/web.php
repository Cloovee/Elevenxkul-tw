<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard-ketua', function () {
    return view('dashboard-ketua.index');
})->middleware(['auth', 'verified'])->name('dashboard.ketua');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';