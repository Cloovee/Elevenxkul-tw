<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
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

Route::get('/absensi-pelatih', function () {
    return view('dashboard-ketua.absensi-pelatih');
})->middleware(['auth', 'verified'])->name('ketua.absensi-pelatih');

Route::get('/absensi-peserta', function () {
    return view('dashboard-ketua.absensi-peserta');
})->middleware(['auth', 'verified'])->name('ketua.absensi-peserta');

require __DIR__.'/auth.php';