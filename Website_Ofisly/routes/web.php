<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuratTugasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('admin.dashboard.index');
})->middleware(['auth', 'verified', 'role.auth'])->name('dashboard');

Route::middleware(['role.auth', 'auth'])->group(function() {
    Route::resource('surat-tugas', SuratTugasController::class);
});

Route::get('/error_code', function() {
    abort(401);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
