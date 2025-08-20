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
    // public function __construct()
    // {
    //     $this->middleware('can:manage_surat_tugas_promotor');
    // }

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
            'nama_kandidat' => 'required|string|max:255',
            'penempatan' => 'required|array|min:1',
            'penempatan.*' => 'string|max:255',
            'tgl_penugasan' => 'required|date',
            'tgl_surat_pembuatan' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $surat = SuratTugasPromotor::findOrFail($id);
            $surat->update([
                ...$validator->validated(),
                'updated_by' => auth()->id(),
            ]);

            Log::info('Surat tugas promotor updated', [
                'id' => $id,
                'by' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Surat Tugas Promotor berhasil diperbarui',
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
     * Download file
     */
    // protected function downloadFile($id, $fileType)
    // {
    //     try {
    //         $surat = SuratTugasPromotor::findOrFail($id);

    //         if (!$surat->$fileType) {
    //             throw new \Exception("File tidak ditemukan");
    //         }

    //         $relativePath = str_replace('/storage/', '', $surat->$fileType);
    //         $filePath = storage_path('app/public/' . $relativePath);

    //         if (!file_exists($filePath)) {
    //             throw new \Exception("File tidak ditemukan di server");
    //         }

    //         $mimeType = mime_content_type($filePath);
    //         $allowedMimes = [
    //             'file_path_pdf' => 'application/pdf',
    //             'file_path_docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    //         ];

    //         if ($mimeType !== $allowedMimes[$fileType]) {
    //             throw new \Exception("Tipe file tidak valid");
    //         }

    //         return response()
    //             ->download($filePath, basename($filePath))
    //             ->deleteFileAfterSend(false);

    //     } catch (\Exception $e) {
    //         Log::error('Error downloading file: ' . $e->getMessage());
    //         return back()
    //             ->with('error', $e->getMessage());
    //     }
    // }

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

    // /**
    //  * Receive files from Flask API
    //  */
    // public function receiveFiles(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'file_docx' => 'required|file|mimes:docx',
    //         'file_pdf' => 'required|file|mimes:pdf',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid file upload',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     try {
    //         // Simpan file DOCX
    //         $docxFile = $request->file('file_docx');
    //         $docxPath = $docxFile->store('surat_tugas_promotor', 'public');

    //         // Simpan file PDF
    //         $pdfFile = $request->file('file_pdf');
    //         $pdfPath = $pdfFile->store('surat_tugas_promotor', 'public');

    //         // Return JSON response dengan path file
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Files uploaded successfully',
    //             'files' => [
    //                 'docx' => '/storage/' . $docxPath,
    //                 'pdf' => '/storage/' . $pdfPath
    //             ]
    //         ]);

    //     } catch (\Exception $e) {
    //         Log::error('Error receiving files from Flask: ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to process files'
    //         ], 500);
    //     }
    // }
}

