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
        Route::get('/get-data/surat-tugas', [SuratTugasController::class, 'getData'])->name('surat-tugas.getData');
});

Route::get('/error_code', function() {
    abort(401);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::prefix('surat-tugas')->group(function() {
//     Route::get('/', [SuratTugasController::class, 'index'])->name('surat-tugas.index');
//     Route::post('/', [SuratTugasController::class, 'store'])->name('surat-tugas.store');
//     Route::get('/data', [SuratTugasController::class, 'getData'])->name('surat-tugas.data');
//     Route::get('/generate-pdf/{id}', [SuratTugasController::class, 'generatePDF'])->name('surat-tugas.generate-pdf');
//     Route::get('/generate-word/{id}', [SuratTugasController::class, 'generateWord'])->name('surat-tugas.generate-word');
// });

// Route untuk mendapatkan data karyawan
Route::get('/get-karyawan', [KaryawanController::class, 'getKaryawan'])->name('get.karyawan');

require __DIR__.'/auth.php';
