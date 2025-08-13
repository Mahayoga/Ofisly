<?php

use App\Http\Controllers\CutiKaryawanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuratTugasPenggantiDriverController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('landing-page');
});

Route::middleware(['role.auth', 'auth'])->group(function () {
    Route::resource('dashboard', DashboardController::class);

    Route::resource('surat-tugas', SuratTugasPenggantiDriverController::class);
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
    
    Route::resource('cuti-karyawan', CutiKaryawanController::class);

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

// // Nyoba Request File
// Route::post('/nyoba/file', function(Request $request) {
//     if ($request->hasFile('file')) {
//         $file = $request->file('file');
//         // $path = $file->storeAs('uploads', $file->getClientOriginalName(), 'public');

//         return response()->json([
//             'success' => true,
//             // 'path' => $path
//         ]);
//     }
//     return response()->json([
//         'success' => false,
//         'message' => 'No file received',
//         'data' => $request->all()
//     ]);
// });

require __DIR__ . '/auth.php';
