<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratTugasPromotor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ArsipSuratTugasPromotorController extends Controller
{
    public function fetchRowData() {
        $suratTugas = SuratTugasPromotor::where('is_arsip', '1')->latest()->get();
        return response()->json([
            'status' => true,
            'data' => $suratTugas
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $arsipPromotor = SuratTugasPromotor::where('is_arsip', 1)->latest()->get();
        return view('admin.arsip.surat-tugas-promotor.index', compact('arsipPromotor'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $dataSurat = SuratTugasPromotor::findOrFail($id);
            $dataSurat->update([
                'is_arsip' => 0,
                'updated_by' => auth()->id()
            ]);

            Log::info('Surat tugas promotor restored from archive', ['id' => $id]);

            return response()->json([
                'status' => true,
                'message' => 'Surat berhasil dikembalikan dari arsip',
                'id' => $id
            ]);
        } catch (\Exception $e) {
            Log::error('Error restoring from archive: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $surat = SuratTugasPromotor::findOrFail($id);

            // Hapus file dengan Storage facade (konsisten)
            $this->deleteAssociatedFiles($surat);

            // Hard delete dari database
            $surat->forceDelete();

            Log::info('Surat tugas promotor permanently deleted from archive', ['id' => $id]);

            return response()->json([
                'status' => true,
                'message' => 'Surat berhasil dihapus permanen',
                'id' => $id
            ]);

        } catch (\Exception $e) {
            Log::error('Error permanent delete from archive: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    /**
     * Delete associated files from storage (konsisten dengan controller utama)
     */
    protected function deleteAssociatedFiles($surat)
    {
        try {
            $filesToDelete = [$surat->file_path_pdf, $surat->file_path_docx];
            foreach ($filesToDelete as $file) {
                if ($file) {
                    $relativePath = str_replace('/storage/', 'public/', $file);
                    if (Storage::exists($relativePath)) {
                        Storage::delete($relativePath);
                        Log::info('File dihapus dari archive.', ['path' => $relativePath]);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Gagal menghapus file terkait dari archive.', ['error' => $e->getMessage()]);
        }
    }
}

