<?php

use App\Models\SuratTugasMandiriModel;
use App\Models\SuratTugasPenggantiDriverModel;
use App\Models\SuratTugasPromotor;
use App\Http\Controllers\SuratTugasPromotorController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

Route::post('/get/info/file', function(Request $request) {
    $dataSurat = SuratTugasPenggantiDriverModel::find($request->id);
    if($dataSurat == null) {
        $dataSurat = SuratTugasMandiriModel::findOrFail($request->id);
    }
    $pathDocx = $dataSurat->file_path_docx;
    $pathPDF = $dataSurat->file_path_pdf;
    if($request->type == 'pdf') {
        if(is_file(public_path() . $pathPDF)) {
            return response()->json([
                'status' => true,
            ]);
        }
    } else if($request->type == 'docx') {
        if(is_file(public_path() . $pathDocx)) {
            return response()->json([
                'status' => true,
            ]);
        }
    }

    return response()->json([
        'status' => false,
    ]);
});

Route::post('/send/surat/pengganti/driver', function(Request $request) {
    $savedFiles = [];

    // Cek file DOCX
    if ($request->hasFile('file_docx')) {
        $file = $request->file('file_docx');
        $path = $file->storeAs(
            'uploads/surat_template',
            $file->getClientOriginalName(),
            'public'
        );
        $savedFiles['docx'] = Storage::url($path);
    }

    // Cek file PDF
    if ($request->hasFile('file_pdf')) {
        $file = $request->file('file_pdf');
        $path = $file->storeAs(
            'uploads/surat_template',
            $file->getClientOriginalName(),
            'public'
        );
        $savedFiles['pdf'] = Storage::url($path);
    }

    if (!empty($savedFiles)) {
        return response()->json([
            'success' => true,
            'files' => $savedFiles
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'No file received',
        'data' => $request->all()
    ]);
});

// ======================================================================
// ROUTES UNTUK SURAT TUGAS PROMOTOR - API
// ======================================================================

// ✅ TAMBAHKAN ROUTE INI - File Check untuk API calls
Route::get('/surat-promotor/{id}/file-check/{type}', [SuratTugasPromotorController::class, 'fileCheck'])
    ->name('api.surat-promotor.file-check');

// Endpoint untuk upload final file dari Flask
Route::post('/surat-promotor/upload-final', [SuratTugasPromotorController::class, 'uploadFinal'])
    ->name('api.surat-promotor.upload-final');

// Endpoint untuk update file paths (alternatif)
Route::post('/surat-promotor/{id}/update-file-paths', [SuratTugasPromotorController::class, 'updateFilePaths'])
    ->name('api.surat-promotor.update-file-paths');

// Endpoint untuk check file status
Route::get('/surat-promotor/{id}/file-status', [SuratTugasPromotorController::class, 'getFileStatus'])
    ->name('api.surat-promotor.file-status');

// Endpoint untuk check generation status (fallback)
Route::get('/surat-promotor/{id}/generation-status', function($id) {
    try {
        $surat = \App\Models\SuratTugasPromotor::findOrFail($id);

        $status = [
            'id' => $id,
            'pdf_exists' => !empty($surat->file_path_pdf),
            'docx_exists' => !empty($surat->file_path_docx),
            'status' => (!empty($surat->file_path_pdf) && !empty($surat->file_path_docx)) ? 'completed' : 'processing'
        ];

        return response()->json(['success' => true, 'status' => $status]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Record not found'], 404);
    }
});

// ✅ TAMBAHKAN ROUTE INI - Health check untuk Flask
Route::get('/flask-health', function() {
    try {
        $apiURL = env('FLASK_API_URL') . '/health';
        $response = Http::timeout(5)->get($apiURL);

        if ($response->successful()) {
            return response()->json(['status' => 'connected', 'flask_status' => $response->json()]);
        }

        return response()->json(['status' => 'error', 'message' => 'Flask server not responding'], 503);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Cannot connect to Flask server'], 503);
    }
});

Route::get('/nyoba/ajax', function() {
    $apiURL = env('FLASK_API_URL') . '/nyoba/ajax';

    $responses = Http::get($apiURL);

    $responsesData = $responses->json();

    if($responses->successful() && $responsesData['status'] == true) {
        return response()->json([
            'status' => 'success',
        ]);
    }
})->name('nyoba.ajax');
