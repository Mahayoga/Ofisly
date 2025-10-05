<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratTugasPromotor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        $suratTugasPromotor = SuratTugasPromotor::where('is_arsip', 0)
            ->latest()
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
            'penempatan' => 'required|string',
            'tgl_penugasan' => 'required|date|after_or_equal:today',
        ], [
            'tgl_penugasan.after_or_equal' => 'Tanggal penugasan harus hari ini atau setelahnya',
            'penempatan.required' => 'Minimal harus ada 1 penempatan',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Validasi gagal');
        }

        try {
            $validated = $validator->validated();

            // Process penempatan
            $penempatanArray = array_filter(array_map('trim', explode(',', $validated['penempatan'])));

            $resultCreate = SuratTugasPromotor::create([
                'nama_kandidat' => $validated['nama_kandidat'],
                'tgl_penugasan' => $validated['tgl_penugasan'],
                'penempatan' => $penempatanArray,
                'tgl_surat_pembuatan' => Carbon::now()->format('Y-m-d'),
                'created_by' => auth()->id(),
            ]);

            Log::info('Surat tugas promotor created', [
                'id' => $resultCreate->id_surat_tugas_promotor,
                'by' => auth()->id()
            ]);

            $this->triggerFileGeneration($resultCreate->id_surat_tugas_promotor);

            return redirect()
                ->route('surat-tugas-promotor.index')
                ->with('success', 'Surat Tugas Promotor berhasil dibuat');

        } catch (\Exception $e) {
            Log::error('Error creating surat tugas promotor: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    /**
     * Fetch all data for DataTables or AJAX requests
     */
    public function fetchRowData()
    {
        try {
            $suratTugas = SuratTugasPromotor::where('is_arsip', 0)->latest()->get();
            return response()->json([
                'status' => true,
                'data' => $suratTugas->toArray()
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching row data: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Error fetching data: ' . $e->getMessage()
            ], 500);
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
        } catch (ModelNotFoundException $e) {
            Log::error('Surat tugas promotor not found: ' . $e->getMessage());
            return back()->with('error', 'Data tidak ditemukan');
        } catch (\Exception $e) {
            Log::error('Error showing surat tugas promotor: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem');
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
        } catch (ModelNotFoundException $e) {
            Log::error('Surat tugas promotor not found for edit: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error editing surat tugas promotor: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'edit_nama_kandidat' => 'required|string|max:255',
            'edit_penempatan' => 'required|string',
            'edit_tgl_penugasan' => 'required|date',
            'edit_tgl_surat_pembuatan' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $surat = SuratTugasPromotor::findOrFail($id);

            // Process penempatan from string to array
            $penempatanArray = array_filter(array_map('trim', explode(',', $request->edit_penempatan)));

            // Delete old files before update
            $this->deleteAssociatedFiles($surat);

            $surat->update([
                'nama_kandidat' => $request->edit_nama_kandidat,
                'penempatan' => $penempatanArray,
                'tgl_penugasan' => $request->edit_tgl_penugasan,
                'tgl_surat_pembuatan' => $request->edit_tgl_surat_pembuatan,
                'updated_by' => auth()->id(),
                'file_path_docx' => null,
                'file_path_pdf' => null,
            ]);

            Log::info('Surat tugas promotor updated', [
                'id' => $id,
                'by' => auth()->id()
            ]);

            // Regenerate files
            $this->triggerFileGeneration($id);

            return response()->json([
                'success' => true,
                'message' => 'Surat Tugas Promotor berhasil diperbarui'
            ]);

        } catch (ModelNotFoundException $e) {
            Log::error('Surat tugas promotor not found for update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error updating surat tugas promotor: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem'
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

        // Hanya tandai sebagai arsip, TIDAK PERLU soft delete di sini
        $surat->update([
            'is_arsip' => 1,
            'updated_by' => auth()->id()
        ]);

        Log::info('Surat tugas promotor moved to archive', [
            'id' => $id,
            'by' => auth()->id()
        ]);

        return redirect()
            ->route('surat-tugas-promotor.index')
            ->with('success', 'Surat Tugas Promotor berhasil dipindahkan ke arsip');

    } catch (ModelNotFoundException $e) {
        Log::error('Surat tugas promotor not found for archive: ' . $e->getMessage());
        return back()->with('error', 'Data tidak ditemukan');
    } catch (\Exception $e) {
        Log::error('Error archiving surat tugas promotor: ' . $e->getMessage());
        return back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
    }
}

    /**
     * Generate PDF for download.
     */
    public function generatePDF($id)
    {
        try {
            $surat = SuratTugasPromotor::findOrFail($id);

            if ($surat->file_path_pdf) {
                $storagePath = str_replace('/storage/', '', $surat->file_path_pdf);

                if (Storage::disk('public')->exists($storagePath)) {
                    $filePath = storage_path('app/public/' . $storagePath);
                    return response()->download($filePath);
                }
            }

            // Jika file tidak ada, trigger regeneration
            $this->triggerFileGeneration($id);
            return response()->json([
                'status' => 'processing',
                'message' => 'File sedang diproses, silakan coba lagi dalam beberapa saat'
            ], 202);

        } catch (ModelNotFoundException $e) {
            Log::error('Surat tugas promotor not found for PDF generation: ' . $e->getMessage());
            abort(404, 'Data tidak ditemukan');
        } catch (\Exception $e) {
            Log::error('Error generating PDF', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            abort(500, 'Terjadi kesalahan saat mengunduh file');
        }
    }

    /**
     * Generate Word for download.
     */
    public function generateWord($id)
    {
        try {
            $surat = SuratTugasPromotor::findOrFail($id);

            if ($surat->file_path_docx) {
                $storagePath = str_replace('/storage/', '', $surat->file_path_docx);

                if (Storage::disk('public')->exists($storagePath)) {
                    $filePath = storage_path('app/public/' . $storagePath);
                    return response()->download($filePath);
                }
            }

            // Jika file tidak ada, trigger regeneration
            $this->triggerFileGeneration($id);
            return response()->json([
                'status' => 'processing',
                'message' => 'File sedang diproses, silakan coba lagi dalam beberapa saat'
            ], 202);

        } catch (ModelNotFoundException $e) {
            Log::error('Surat tugas promotor not found for Word generation: ' . $e->getMessage());
            abort(404, 'Data tidak ditemukan');
        } catch (\Exception $e) {
            Log::error('Error generating Word', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            abort(500, 'Terjadi kesalahan saat mengunduh file');
        }
    }

    /**
     * Upload final files from Flask - DENGAN WebSocket Support
     */
    public function uploadFinal(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'surat_id' => 'required|exists:surat_tugas_promotor,id_surat_tugas_promotor',
                'file_docx' => 'required|file|mimes:docx',
                'file_pdf' => 'required|file|mimes:pdf',
            ]);

            if ($validator->fails()) {
                Log::error('Upload final validation failed', ['errors' => $validator->errors()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal'
                ], 422);
            }

            $suratId = $request->surat_id;
            $surat = SuratTugasPromotor::findOrFail($suratId);

            Log::info('Memulai proses upload file dari Flask', ['id' => $suratId]);

            // Simpan file
            $docxPath = $request->file('file_docx')->store('surat_promotor', 'public');
            $pdfPath = $request->file('file_pdf')->store('surat_promotor', 'public');

            // Update database
            $surat->update([
                'file_path_docx' => '/storage/' . $docxPath,
                'file_path_pdf' => '/storage/' . $pdfPath,
            ]);

            Log::info('File upload final completed', [
                'id' => $suratId,
                'docx_path' => '/storage/' . $docxPath,
                'pdf_path' => '/storage/' . $pdfPath
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File berhasil diupload dan database diperbarui'
            ]);

        } catch (ModelNotFoundException $e) {
            Log::error('Surat tugas promotor not found for upload final: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error in upload final', [
                'id' => $request->surat_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Trigger File Generation
     */
    protected function triggerFileGeneration($id)
    {
        try {
            $surat = SuratTugasPromotor::findOrFail($id);
            $apiURL = env('FLASK_API_URL') . '/generate/surat/promotor';

            Log::info('Mengirim permintaan pembuatan file ke Flask.', ['id' => $id]);

            $response = Http::timeout(30)->post($apiURL, [
                'surat_data' => $surat->toArray()
            ]);

            if (!$response->successful()) {
                throw new \Exception('HTTP Error: ' . $response->status() . ' - ' . $response->body());
            }

            $responseData = $response->json();

            if (isset($responseData['success']) && $responseData['success'] === true) {
                return [
                    'success' => true,
                    'websocket_required' => $responseData['websocket_required'] ?? true,
                    'surat_id' => $responseData['surat_id'] ?? $id,
                    'message' => $responseData['message'] ?? 'Proses pembuatan file dimulai'
                ];
            }

            if (isset($responseData['status']) && $responseData['status'] == 'success') {
                return [
                    'success' => true,
                    'websocket_required' => $responseData['websocket_required'] ?? true,
                    'surat_id' => $responseData['surat_id'] ?? $id,
                    'message' => $responseData['message'] ?? 'Proses pembuatan file dimulai'
                ];
            }

            $errorMessage = $responseData['message'] ?? 'Unknown error from Flask';
            throw new \Exception($errorMessage);

        } catch (\Exception $e) {
            Log::error('Error triggering file generation', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Gagal memulai proses: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Delete associated files from storage.
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
                        Log::info('File lama dihapus.', ['path' => $relativePath]);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Gagal menghapus file terkait.', ['error' => $e->getMessage()]);
        }
    }

    /**
     * File Check - Handles requests from web routes and API.
     */
    public function fileCheck($id, $type)
    {
        try {
            $dataSurat = SuratTugasPromotor::findOrFail($id);

            $pathToCheck = ($type == 'pdf') ? $dataSurat->file_path_pdf : $dataSurat->file_path_docx;

            if ($pathToCheck) {
                $storagePath = str_replace('/storage/', '', $pathToCheck);
                $fileExists = Storage::disk('public')->exists($storagePath);

                if ($fileExists) {
                    Log::info('File ditemukan di storage', [
                        'id' => $id,
                        'type' => $type,
                        'path' => $storagePath
                    ]);
                    return response()->json([
                        'status' => true,
                        'message' => 'File siap diunduh'
                    ]);
                }
            }

            // File tidak ada di storage, cek apakah Flask sedang memproses
            Log::warning('File tidak ditemukan di storage, checking Flask status', [
                'id' => $id,
                'type' => $type,
                'expected_path' => $pathToCheck
            ]);

            $flaskCheckURL = env('FLASK_API_URL') . '/check/generate/run';
            $response = Http::timeout(3)->post($flaskCheckURL, ['id' => $id]);

            if ($response->successful() && $response->json()['status'] === true) {
                Log::info('Flask sedang memproses file', ['id' => $id]);
                return response()->json([
                    'status' => 'processing',
                    'message' => 'File masih dalam proses generate, mohon tunggu...'
                ]);
            }

            // File tidak ada dan Flask tidak memproses -> trigger regenerate
            Log::warning('File tidak ditemukan dan Flask tidak memproses, triggering regenerate', ['id' => $id]);
            $this->triggerFileGeneration($id);

            return response()->json([
                'status' => 'regenerating',
                'message' => 'File tidak ditemukan. Proses pembuatan ulang dimulai.'
            ]);

        } catch (ModelNotFoundException $e) {
            Log::error('Data surat tidak ditemukan', ['id' => $id]);
            return response()->json([
                'status' => false,
                'message' => 'Data surat tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error dalam file check', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan internal saat memeriksa file.'
            ], 500);
        }
    }

    /**
     * Update file paths after generation from Flask
     */
    public function updateFilePaths(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file_path_pdf' => 'nullable|string',
                'file_path_docx' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $surat = SuratTugasPromotor::findOrFail($id);

            $updates = [];
            if ($request->has('file_path_pdf')) {
                $updates['file_path_pdf'] = $request->file_path_pdf;
            }
            if ($request->has('file_path_docx')) {
                $updates['file_path_docx'] = $request->file_path_docx;
            }

            $surat->update($updates);

            Log::info('File paths updated', [
                'id' => $id,
                'pdf_path' => $request->file_path_pdf,
                'docx_path' => $request->file_path_docx
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File paths updated successfully'
            ]);

        } catch (ModelNotFoundException $e) {
            Log::error('Surat tugas promotor not found for update file paths: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error updating file paths: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem.'
            ], 500);
        }
    }

    /**
     * Get current file status
     */
    public function getFileStatus($id)
    {
        try {
            $surat = SuratTugasPromotor::findOrFail($id);

            // Check if files actually exist in storage
            $pdfExists = false;
            $docxExists = false;

            if ($surat->file_path_pdf) {
                $pdfPath = str_replace('/storage/', '', $surat->file_path_pdf);
                $pdfExists = Storage::disk('public')->exists($pdfPath);
            }

            if ($surat->file_path_docx) {
                $docxPath = str_replace('/storage/', '', $surat->file_path_docx);
                $docxExists = Storage::disk('public')->exists($docxPath);
            }

            $status = [
                'pdf' => $pdfExists,
                'docx' => $docxExists,
                'id' => $id,
                'file_path_pdf' => $surat->file_path_pdf,
                'file_path_docx' => $surat->file_path_docx
            ];

            return response()->json([
                'success' => true,
                'status' => $status
            ]);

        } catch (ModelNotFoundException $e) {
            Log::error('Surat tugas promotor not found for file status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error getting file status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting file status'
            ], 500);
        }
    }

    /**
     * Check Flask Status - UNTUK FALLBACK
     */
    public function checkFlaskStatus($id)
    {
        try {
            $flaskCheckURL = env('FLASK_API_URL') . '/check/status/' . $id;
            $response = Http::timeout(5)->get($flaskCheckURL);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'status' => 'unknown',
                'message' => 'Unable to reach Flask server'
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking Flask status', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Check failed'
            ]);
        }
    }
}
