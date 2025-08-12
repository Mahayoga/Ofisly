<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Nyoba Request File
Route::post('/nyoba/file', function(Request $request) {
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $path = $file->storeAs('uploads/surat_template', $file->getClientOriginalName(), 'public');

        return response()->json([
            'success' => true,
            'path' => $path,
        ]);
    }
    return response()->json([
        'success' => false,
        'message' => 'No file received',
        'data' => $request->all()
    ]);
});