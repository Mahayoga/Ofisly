<?php

use App\Http\Controllers\CutiKaryawanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuratTugasController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('landing-page');
});

Route::middleware(['role.auth', 'auth'])->group(function () {
    Route::resource('dashboard', DashboardController::class);

    Route::resource('surat-tugas', SuratTugasController::class);
        Route::get('/get-data/surat-tugas', [SuratTugasController::class, 'getData'])->name('surat-tugas.getData');
        Route::prefix('surat-tugas')->group(function () {
            Route::get('/', [SuratTugasController::class, 'index'])->name('surat-tugas.index');
            Route::post('/', [SuratTugasController::class, 'store'])->name('surat-tugas.store');
            Route::get('/{id}/edit', [SuratTugasController::class, 'edit'])->name('surat-tugas.edit');
            Route::put('/{id}', [SuratTugasController::class, 'update'])->name('surat-tugas.update');
            Route::delete('/{id}', [SuratTugasController::class, 'destroy'])->name('surat-tugas.destroy');
            Route::get('/generate-pdf/{id}', [SuratTugasController::class, 'generatePDF'])->name('surat-tugas.generate-pdf');
            Route::get('/generate-word/{id}', [SuratTugasController::class, 'generateWord'])->name('surat-tugas.generate-word');
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
