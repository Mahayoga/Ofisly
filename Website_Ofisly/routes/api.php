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

Route::post('/send/surat/promotor', function(Request $request) {
    $request->validate([
        'file_docx' => 'required|file|mimes:docx',
        'file_pdf' => 'required|file|mimes:pdf'
    ]);

    $savedFiles = [];

    try {
        // Handle DOCX file
        if ($request->hasFile('file_docx')) {
            $docxFile = $request->file('file_docx');
            $docxName = 'promotor_'.time().'_'.uniqid().'.'.$docxFile->extension();

            $docxPath = $docxFile->storeAs(
                'uploads/surat_tugas_promotor',
                $docxName,
                'public'
            );
            $savedFiles['docx'] = Storage::url($docxPath);
        }

        // Handle PDF file
        if ($request->hasFile('file_pdf')) {
            $pdfFile = $request->file('file_pdf');
            $pdfName = 'promotor_'.time().'_'.uniqid().'.'.$pdfFile->extension();

            $pdfPath = $pdfFile->storeAs(
                'uploads/surat_tugas_promotor',
                $pdfName,
                'public'
            );
            $savedFiles['pdf'] = Storage::url($pdfPath);
        }

        return response()->json([
            'success' => true,
            'message' => 'Files uploaded successfully',
            'files' => $savedFiles
        ]);

    } catch (\Exception $e) {
        // Delete files if error occurs
        foreach ($savedFiles as $type => $url) {
            $path = str_replace('/storage', '', parse_url($url, PHP_URL_PATH));
            Storage::disk('public')->delete($path);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to upload files',
            'error' => $e->getMessage()
        ], 500);
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
