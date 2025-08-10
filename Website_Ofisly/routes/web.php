<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuratTugasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing-page');
});
Route::get('/dashboard', function () {
    return view('admin.dashboard.index');
})->middleware(['auth', 'verified', 'role.auth'])->name('dashboard');

Route::middleware(['role.auth', 'auth'])->group(function () {
    Route::resource('surat-tugas', SuratTugasController::class);
});

//sementara 
Route::middleware(['role.auth', 'auth'])->get('/cuti-karyawan', function () {
    return view('admin.cuti_karyawan.index');})->name('cuti-karyawan');

Route::get('/error_code', function () {
    abort(401);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('surat-tugas')->group(function () {
    Route::get('/', [SuratTugasController::class, 'index'])->name('surat-tugas.index');
    Route::post('/', [SuratTugasController::class, 'store'])->name('surat-tugas.store');
    Route::get('/{id}/edit', [SuratTugasController::class, 'edit'])->name('surat-tugas.edit');
    Route::put('/{id}', [SuratTugasController::class, 'update'])->name('surat-tugas.update');
    Route::delete('/{id}', [SuratTugasController::class, 'destroy'])->name('surat-tugas.destroy');
    Route::get('/generate-pdf/{id}', [SuratTugasController::class, 'generatePDF'])->name('surat-tugas.generate-pdf');
    Route::get('/generate-word/{id}', [SuratTugasController::class, 'generateWord'])->name('surat-tugas.generate-word');
});
require __DIR__ . '/auth.php';
