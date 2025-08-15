<?php

use App\Http\Controllers\CutiKaryawanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuratTugasPenggantiDriverController;
use App\Http\Controllers\SuratTugasPromotorController;
use App\Http\Controllers\SuratPenempatanDriverMandiriController;
use App\Http\Controllers\SuratTugasController;
use App\Http\Controllers\LowonganPekerjaanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('landing-page');
});

Route::middleware(['role.auth', 'auth'])->group(function () {
    Route::resource('dashboard', DashboardController::class);

        // Surat Tugas Promotor Routes
        Route::resource('surat-tugas-promotor', SuratTugasPromotorController::class);
        Route::prefix('surat-tugas-promotor')->group(function () {
            Route::get('/', [SuratTugasPromotorController::class, 'index'])->name('surat-tugas-promotor.index');
            Route::post('/', [SuratTugasPromotorController::class, 'store'])->name('surat-tugas-promotor.store');
            Route::get('/{id}/edit', [SuratTugasPromotorController::class, 'edit'])->name('surat-tugas-promotor.edit');
            Route::put('/{id}', [SuratTugasPromotorController::class, 'update'])->name('surat-tugas-promotor.update');
            Route::delete('/{id}', [SuratTugasPromotorController::class, 'destroy'])->name('surat-tugas-promotor.destroy');
            Route::get('/generate-pdf/{id}', [SuratTugasPromotorController::class, 'generatePDF'])->name('surat-tugas-promotor.generate-pdf');
            Route::get('/generate-word/{id}', [SuratTugasPromotorController::class, 'generateWord'])->name('surat-tugas-promotor.generate-word');
            Route::post('/generate/file', [SuratTugasPromotorController::class, 'generateFile'])->name('surat-tugas-promotor.generate-file');
        });

        // Surat Tugas Pengganti Driver Routes
        Route::get('/get-data/surat-tugas', [SuratTugasPenggantiDriverController::class, 'getData'])->name('surat-tugas.getData');
        Route::prefix('surat-tugas')->group(function () {
            Route::get('/', [SuratTugasPenggantiDriverController::class, 'index'])->name('surat-tugas.index');
            Route::post('/', [SuratTugasPenggantiDriverController::class, 'store'])->name('surat-tugas.store');
            Route::get('/{id}/edit', [SuratTugasPenggantiDriverController::class, 'edit'])->name('surat-tugas.edit');
            Route::put('/{id}', [SuratTugasPenggantiDriverController::class, 'update'])->name('surat-tugas.update');
            Route::delete('/{id}', [SuratTugasPenggantiDriverController::class, 'destroy'])->name('surat-tugas.destroy');
            Route::get('/generate-pdf/{id}', [SuratTugasPenggantiDriverController::class, 'generatePDF'])->name('surat-tugas.generate-pdf');
            Route::get('/generate-word/{id}', [SuratTugasPenggantiDriverController::class, 'generateWord'])->name('surat-tugas.generate-word');
            Route::post('/generate/file', [SuratTugasPenggantiDriverController::class, 'generateFile'])->name('surat-tugas.generate-file');
        });

    //Surat Tugas Penempatan Driver Mandiri Routes
    Route::resource('surat-penempatan-driver-mandiri', SuratPenempatanDriverMandiriController::class);
    Route::get('/generate-pdf/{id}', [SuratPenempatanDriverMandiriController::class, 'generatePDF'])->name('surat-penempatan-driver-mandiri.generate-pdf');
    Route::get('/generate-word/{id}', [SuratPenempatanDriverMandiriController::class, 'generateWord'])->name('surat-penempatan-driver-mandiri.generate-word');
    Route::post('/generate/file', [SuratPenempatanDriverMandiriController::class, 'generateFile'])->name('surat-penempatan-driver-mandiri.generate-file');


    Route::resource('cuti-karyawan', CutiKaryawanController::class);
    //lowongan pekerjaan
    Route::resource('lowongan-pekerjaan', LowonganPekerjaanController::class);
    // Route::get('/', [LowonganPekerjaanController::class, 'landing'])->name('landing');
    // Route::get('/lowongan/detail/{id}', [LowonganPekerjaanController::class, 'showLanding'])->name('lowongan-pekerjaan.detail');
    // Blank Page
    Route::get('/blank', function() {
        return view('admin.layout.blank');
    })->name('blank.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
