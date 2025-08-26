<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratTugasPromotor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SuratTugasPromotorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suratTugasPromotor = SuratTugasPromotor::latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.surat_tugas_promotor.index', compact('suratTugasPromotor'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.surat_tugas_promotor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    $validator = Validator::make($request->all(), [
        'nama_kandidat' => 'required|string|max:255',
        'penempatan' => 'required',
        'tgl_penugasan' => 'required|date|after_or_equal:today',
    ], [
        'tgl_penugasan.after_or_equal' => 'Tanggal penugasan harus hari ini atau setelahnya',
        'penempatan.required' => 'Minimal harus ada 1 penempatan',
    ]);

    // Konversi penempatan ke array jika berupa string
    $penempatan = $request->penempatan;
    if (is_string($penempatan)) {
        $penempatan = explode(',', $penempatan);
        $penempatan = array_map('trim', $penempatan);
        $penempatan = array_filter($penempatan);
    }

    if (empty($penempatan)) {
        return back()
            ->withErrors(['penempatan' => 'Minimal harus ada 1 penempatan'])
            ->withInput()
            ->with('error', 'Validasi gagal');
    }

        try {
            $validated = $validator->validated();

            $resultCreate = SuratTugasPromotor::create([
                ...$validated,
                'tgl_surat_pembuatan' => Carbon::now()->format('Y-m-d'),
                'created_by' => auth()->id(),
            ]);

            Log::info('Surat tugas promotor created', [
                'id' => $resultCreate->id_surat_tugas_promotor,
                'by' => auth()->id()
            ]);

            return redirect()
                ->route('surat-tugas-promotor.index')
                ->with([
                    'success' => 'Surat Tugas Promotor berhasil dibuat',
                    'action' => 'generate_surat',
                    'id_generate' => $resultCreate->id_surat_tugas_promotor
                ]);

        } catch (\Exception $e) {
            Log::error('Error creating surat tugas promotor: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $surat = SuratTugasPromotor::findOrFail($id);
            return view('admin.surat_tugas_promotor.show', compact('surat'));
        } catch (\Exception $e) {
            Log::error('Error showing surat tugas promotor: ' . $e->getMessage());
            return back()
                ->with('error', 'Data tidak ditemukan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $surat = SuratTugasPromotor::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $surat->only([
                    'nama_kandidat',
                    'penempatan',
                    'tgl_penugasan',
                    'tgl_surat_pembuatan',
                    'file_path_docx',
                    'file_path_pdf'
                ])
            ]);
        } catch (\Exception $e) {
            Log::error('Error editing surat tugas promotor: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'edit_nama_kandidat' => 'required|string|max:255',
            'edit_penempatan' => 'required',
            'edit_tgl_penugasan' => 'required|date',
            'edit_tgl_surat_pembuatan' => 'required|date',
        ]);

        // Konversi penempatan ke array jika berupa string JSON
        $penempatan = $request->edit_penempatan;
        if (is_string($penempatan) && json_decode($penempatan) !== null) {
            $penempatan = json_decode($penempatan, true);
        } elseif (is_string($penempatan)) {
            $penempatan = explode(',', $penempatan);
            $penempatan = array_map('trim', $penempatan);
            $penempatan = array_filter($penempatan);
        }

        if (empty($penempatan)) {
            return response()->json([
                'success' => false,
                'errors' => ['edit_penempatan' => ['Minimal harus ada 1 penempatan']]
            ], 422);
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

            try {
            $surat = SuratTugasPromotor::findOrFail($id);

            // HAPUS FILE LAMA SEBELUM UPDATE
            $this->deleteAssociatedFiles($surat);

            $surat->update([
                'nama_kandidat' => $request->edit_nama_kandidat,
                'penempatan' => $penempatan,
                'tgl_penugasan' => $request->edit_tgl_penugasan,
                'tgl_surat_pembuatan' => $request->edit_tgl_surat_pembuatan,
                'updated_by' => auth()->id(),
                // KOSONGKAN PATH FILE UNTUK DIGENERATE ULANG
                'file_path_docx' => null,
                'file_path_pdf' => null,
            ]);

            Log::info('Surat tugas promotor updated', [
                'id' => $id,
                'by' => auth()->id()
            ]);

            // KEMBALIKAN RESPONSE DENGAN DATA UNTUK GENERATE FILE
            return response()->json([
                'success' => true,
                'message' => 'Surat Tugas Promotor berhasil diperbarui',
                'action' => 'generate_surat', // ← TAMBAHKAN INI
                'id_generate' => $id // ← TAMBAHKAN INI
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating surat tugas promotor: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $surat = SuratTugasPromotor::findOrFail($id);

            // Hapus file terkait jika ada
            $this->deleteAssociatedFiles($surat);

            $surat->delete();

            Log::info('Surat tugas promotor deleted', [
                'id' => $id,
                'by' => auth()->id()
            ]);

            return redirect()
                ->route('surat-tugas-promotor.index')
                ->with('success', 'Surat Tugas Promotor berhasil dihapus');

        } catch (\Exception $e) {
            Log::error('Error deleting surat tugas promotor: ' . $e->getMessage());
            return back()
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    /**
     * Generate PDF
     */
    public function generatePDF($id)
    {
        try {
            $surat = SuratTugasPromotor::findOrFail($id);
            $relativePath = str_replace('/storage/', '', $surat->file_path_pdf);
            $filePath = storage_path('app/public/' . $relativePath);
            return response()->download($filePath)->deleteFileAfterSend(false);

        } catch (\Exception $e) {
            abort(500, 'Hehe');
        }
    }

    /**
     * Generate Word
     */
    public function generateWord($id)
    {
        try {
            $surat = SuratTugasPromotor::findOrFail($id);
            $relativePath = str_replace('/storage/', '', $surat->file_path_docx);
            $filePath = storage_path('app/public/' . $relativePath);
            return response()->download($filePath)->deleteFileAfterSend(false);

        } catch (\Exception $e) {
            abort(500, 'Hehe');
        }
    }

    /**
     * Generate File
     */
    public function generateFile(Request $request)
    {
        $apiURL = env('FLASK_API_URL') . '/generate/surat/promotor';
        $model= new SuratTugasPromotor();
        $responses = Http::post($apiURL, [
            'id_surat_tugas_promotor' => $request->id,
            'table' => $model->getTable()
        ]);

        $responsesData = $responses->json();

        if ($responses->successful() && $responsesData['status'] == 'success') {
            return response()->json([
                'status' => 'success',
            ]);
        }
        return response()->json([
            'status' => 'error',
        ]);
    }

    /**
     * Delete associated files
     */
    protected function deleteAssociatedFiles($surat)
    {
        try {
            $filesToDelete = [
                $surat->file_path_pdf,
                $surat->file_path_docx
            ];

            foreach ($filesToDelete as $file) {
                if ($file) {
                    $relativePath = str_replace('/storage/', '', $file);
                    $filePath = storage_path('app/public/' . $relativePath);

                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error deleting associated files: ' . $e->getMessage());
        }
    }

    /**
     * File Check
     */
    public function fileCheck($id, $type) {
        $apiURL = env('FLASK_API_URL') . '/check/generate/run';
        $dataSurat = SuratTugasPromotor::findOrFail($id);
        $pathDocx = $dataSurat->file_path_docx;
        $pathPDF = $dataSurat->file_path_pdf;
        if($type == 'pdf') {
            if(is_file(public_path() . $pathPDF)) {
                return response()->json([
                    'status' => true,
                ]);
            } else {
                $responses = Http::post($apiURL, [
                    'id' => $id
                ]);
                $responsesData = $responses->json();
                if($responses->successful()) {
                    if($responsesData['status']) {
                        return response()->json([
                            'status' => true,
                            'msg' => 'File ini masih proses generate yaa, mohon bersabar!'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'msg' => 'File ini sepertinya terhapus, masukan datanya lagi yaa!'
                        ]);
                    }
                }
            }
        } else if($type == 'docx') {
            if(is_file(public_path() . $pathDocx)) {
                return response()->json([
                    'status' => true,
                ]);
            } else {
                $responses = Http::post($apiURL, [
                    'id' => $id
                ]);
                $responsesData = $responses->json();
                if($responses->successful()) {
                    if($responsesData['status']) {
                        return response()->json([
                            'status' => true,
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'msg' => 'File ini masih proses generate yaa, mohon bersabar!'
                        ]);
                    }
                }
            }
        }

        return response()->json([
            'status' => false,
            'data' => $dataSurat,
            'type' => $type
        ]);
    }
}

