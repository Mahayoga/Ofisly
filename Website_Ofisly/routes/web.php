<?php

use App\Http\Controllers\CutiKaryawanController;
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

Route::resource('daftar-lowongan', DaftarLowonganController::class);


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
            // Route::post('/send/surat/promotor', [SuratTugasPromotorController::class, 'receiveFiles']);
            Route::get('/generate-pdf/{id}', [SuratTugasPromotorController::class, 'generatePDF'])->name('surat-tugas-promotor.generate-pdf');
            Route::get('/generate-word/{id}', [SuratTugasPromotorController::class, 'generateWord'])->name('surat-tugas-promotor.generate-word');
            Route::post('/generate/file', [SuratTugasPromotorController::class, 'generateFile'])->name('surat-tugas-promotor.generate-file');
            Route::get('/file/check/{id}/{type}', [SuratTugasPromotorController::class, 'fileCheck'])->name('surat-tugas-promotor.file-check');
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
    Route::resource('pendaftar-lowongan', LowonganPekerjaanController::class);



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
