<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashAdminController;
use App\Http\Controllers\Admin\SiswaController;
use Illuminate\Support\Facades\Auth; 


Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__.'/auth.php';

Route::get('/dashboard', function () {
    $user = Auth::user(); 
    
    return match ($user->role) {
        'Admin'    => redirect()->route('admin.dashboard'),
        'Pembina'  => redirect()->route('pembina.dashboard'),
        'Ketua'    => redirect()->route('ketua.dashboard'),
        default    => redirect('/'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('/admin')
    ->middleware(['auth', 'verified', 'role:Admin'])
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashAdminController::class, 'index'])->name('dashboard');

        Route::prefix('/siswa')->name('siswa.')->group(function () {
            Route::get('/', [SiswaController::class, 'index'])->name('index');
            Route::get('/create', [SiswaController::class, 'create'])->name('create');
            Route::post('/store', [SiswaController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [SiswaController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SiswaController::class, 'update'])->name('update');
            Route::delete('/{id}', [SiswaController::class, 'destroy'])->name('destroy');

            Route::get('/import', [SiswaController::class, 'showImportForm'])->name('import.form');
            Route::post('/import', [SiswaController::class, 'import'])->name('import');
            Route::get('/export', [SiswaController::class, 'export'])->name('export');
            Route::get('/template', [SiswaController::class, 'downloadTemplate'])->name('template');
        });
    });

Route::prefix('/pembina')
    ->middleware(['auth', 'verified', 'role:Pembina'])
    ->name('pembina.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('pembina.dashboard');
        })->name('dashboard');
    });

Route::prefix('/ketua')
    ->middleware(['auth', 'verified', 'role:Ketua'])
    ->name('ketua.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('ketua.dashboard');
        })->name('dashboard');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});