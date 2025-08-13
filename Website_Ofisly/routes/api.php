<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


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

Route::get('/nyoba/ajax', function() {
    return response()->json([
        'status' => 'success',
        // 'data' => $request->all()
    ]);
})->name('nyoba.ajax');
