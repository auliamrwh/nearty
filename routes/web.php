<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TitipanController;
use App\Http\Controllers\UlasanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Modul Titipan (CRUD pembeli) - dikerjakan oleh Hawa
    Route::resource('titipan', TitipanController::class);
    Route::delete('/titipan/{id}/restore', [TitipanController::class, 'restore'])->name('titipan.restore');
    Route::get('/titipan-terhapus', [TitipanController::class, 'trashed'])->name('titipan.trashed');

    // Mode Driver & Ulasan - dikerjakan oleh Regina
    // Halaman /driver & tombol toggle bisa diakses siapa saja (untuk mengaktifkan mode driver).
    // Aksi ambil order & update status hanya untuk user yang is_driver_active (middleware 'driver').
    Route::get('/driver', [DriverController::class, 'index'])->name('driver.index');
    Route::post('/driver/toggle', [DriverController::class, 'toggle'])->name('driver.toggle');

    Route::middleware('driver')->group(function () {
        Route::post('/driver/{titipan}/ambil', [DriverController::class, 'ambil'])->name('driver.ambil');
        Route::patch('/driver/{titipan}/status', [DriverController::class, 'updateStatus'])->name('driver.status');
    });

    Route::get('/ulasan', [UlasanController::class, 'index'])->name('ulasan.index');
    Route::post('/titipan/{titipan}/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');
    Route::delete('/ulasan/{ulasan}', [UlasanController::class, 'destroy'])->name('ulasan.destroy');
});

// Panel Admin (role & permission) - dikerjakan oleh Aulia
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/trashed', [AdminUserController::class, 'trashed'])->name('users.trashed');
    Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.role');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{id}/restore', [AdminUserController::class, 'restore'])->name('users.restore');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/test-blade', function () {
    return view('test');
});

require __DIR__.'/auth.php';
