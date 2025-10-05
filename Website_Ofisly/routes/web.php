<?php

use App\Http\Controllers\CutiKaryawanController;
use App\Http\Controllers\DaftarLowonganAdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuratTugasPromotorController;
use App\Http\Controllers\SuratTugasPenggantiDriverController;
use App\Http\Controllers\SuratTugasMandiriController;
use App\Http\Controllers\SuratTugasController;
use App\Http\Controllers\LowonganPekerjaanController;
use App\Http\Controllers\DaftarLowonganController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('landing-page');
})->name('welcome');

Route::resource('daftar-lowongan', DaftarLowonganController::class)->only(['index','show']);
Route::get('daftar-lowongan/{id_lowongan_pekerjaan}/daftar', [DaftarLowonganController::class, 'create'])->name('daftar-lowongan.create');
Route::post('daftar-lowongan/{id_lowongan_pekerjaan}/daftar', [DaftarLowonganController::class, 'store'])->name('daftar-lowongan.store');


Route::middleware(['role.auth', 'auth'])->group(function () {
    Route::resource('dashboard', DashboardController::class);

    // Surat Tugas Promotor Routes
    Route::prefix('surat-tugas-promotor')->name('surat-tugas-promotor.')->group(function () {
        // CRUD Routes
        Route::get('/', [SuratTugasPromotorController::class, 'index'])->name('index');
        Route::get('/create', [SuratTugasPromotorController::class, 'create'])->name('create');
        Route::post('/', [SuratTugasPromotorController::class, 'store'])->name('store');
        Route::get('/{id}', [SuratTugasPromotorController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [SuratTugasPromotorController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SuratTugasPromotorController::class, 'update'])->name('update');
        Route::delete('/{id}', [SuratTugasPromotorController::class, 'destroy'])->name('destroy');

        // File Generation Routes
        Route::get('/{id}/download/pdf', [SuratTugasPromotorController::class, 'generatePDF'])->name('generate-pdf');
        Route::get('/{id}/download/word', [SuratTugasPromotorController::class, 'generateWord'])->name('generate-word');
        Route::post('/generate-file', [SuratTugasPromotorController::class, 'generateFile'])->name('generate-file');

        // File Management Routes
        // Di routes/web.php
        Route::get('/check/flask-status/{id}', [SuratTugasPromotorController::class, 'checkFlaskStatus'])->name('check-flask-status');
        Route::get('/{id}/file-check/{type}', [SuratTugasPromotorController::class, 'fileCheck'])->name('file-check');
        Route::get('/{id}/file-status', [SuratTugasPromotorController::class, 'getFileStatus'])->name('file-status');
        Route::post('/{id}/update-file-paths', [SuratTugasPromotorController::class, 'updateFilePaths'])->name('update-file-paths');
    });

    Route::resource('surat-tugas', SuratTugasPenggantiDriverController::class);
        Route::prefix('surat-tugas')->group(function () {
            Route::get('/get/latest/data', [SuratTugasPenggantiDriverController::class, 'fetchRowData'])->name('surat-tugas.fetchRowData');
            Route::get('/generate-pdf/{id}', [SuratTugasPenggantiDriverController::class, 'generatePDF'])->name('surat-tugas.generate-pdf');
            Route::get('/generate-word/{id}', [SuratTugasPenggantiDriverController::class, 'generateWord'])->name('surat-tugas.generate-word');
            Route::post('/generate/file', [SuratTugasPenggantiDriverController::class, 'generateFile'])->name('surat-tugas.generate-file');
            Route::get('/file/check/{id}/{type}', [SuratTugasPenggantiDriverController::class, 'fileCheck'])->name('surat-tugas.file-check');
        });

    //route penempatan driver mandiri
    Route::resource('surat-tugas-mandiri', SuratTugasMandiriController::class);
    Route::get('/generate-pdf/{id}', [SuratTugasMandiriController::class, 'generatePDF'])->name('surat-tugas-mandiri.generate-pdf');
    Route::get('/generate-word/{id}', [SuratTugasMandiriController::class, 'generateWord'])->name('surat-tugas-mandiri.generate-word');
    Route::post('/generate/file', [SuratTugasMandiriController::class, 'generateFile'])->name('surat-tugas-mandiri.generate-file');


    Route::resource('cuti-karyawan', CutiKaryawanController::class);

    //lowongan pekerjaan
    Route::resource('lowongan-pekerjaan', LowonganPekerjaanController::class);
    Route::resource('pendaftar-lowongan', DaftarLowonganAdminController::class);



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

// Route::get('/daftar-lowongan', [App\Http\Controllers\DaftarLowonganController::class, 'index'])->name('daftar-lowongan.index');
// Route::get('/daftar-lowongan/{id_lowongan_pekerjaan}', [DaftarLowonganController::class, 'show'])->name('daftar-lowongan.show');

require __DIR__ . '/auth.php';
