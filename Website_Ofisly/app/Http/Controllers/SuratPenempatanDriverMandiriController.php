<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratPenempatanDriverMandiriModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PDF;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class SuratPenempatanDriverMandiriController extends Controller
{
    public function index()
    {
        $suratPenempatan = SuratPenempatanDriverMandiriModel::latest()->orderBy('created_at', 'desc')->get();
        return view('admin.surat-penempatan-driver-mandiri.index', compact('suratPenempatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat' => 'required|string|max:30',
            'nama_kandidat' => 'required|string|max:255',
            'jabatan_kandidat' => 'required|string|max:255',
            'tgl_mulai_penempatan' => 'required|date',
        ]);

        try {
            $resultCreate = SuratPenempatanDriverMandiriModel::create([
                'nomor_surat' => $request->nomor_surat,
                'nama_kandidat' => $request->nama_kandidat,
                'jabatan_kandidat' => $request->jabatan_kandidat,
                'tgl_mulai_penempatan' => $request->tgl_mulai_penempatan,
            ]);

            return redirect()->route('surat-penempatan-driver-mandiri.index')
                ->with([
                    'success' => 'Surat Penempatan Driver Mandiri berhasil dibuat',
                    'action' => 'generate_surat',
                    'id_generate' => $resultCreate->id_surat_penempatan
                ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $suratPenempatan = SuratPenempatanDriverMandiriModel::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => [
                'nomor_surat' => $suratPenempatan->nomor_surat,
                'nama_kandidat' => $suratPenempatan->nama_kandidat,
                'jabatan_kandidat' => $suratPenempatan->jabatan_kandidat,
                'tgl_mulai_penempatan' => $suratPenempatan->tgl_mulai_penempatan,
                'file_path_docx' => $suratPenempatan->file_path_docx,
                'file_path_pdf' => $suratPenempatan->file_path_pdf,
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        ($request->all());
        $request->validate([
            'nomor_surat' => 'required|string|max:30',
            'nama_kandidat' => 'required|string|max:255',
            'jabatan_kandidat' => 'required|string|max:255',
            'tgl_mulai_penempatan' => 'required|date',
        ]);

        try {
            $suratPenempatan = SuratPenempatanDriverMandiriModel::findOrFail($id);
            $suratPenempatan->update([
                'nomor_surat' => $request->nomor_surat,
                'nama_kandidat' => $request->nama_kandidat,
                'jabatan_kandidat'=> $request->jabatan_kandidat,
                'tgl_mulai_penempatan' => $request->tgl_mulai_penempatan,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Surat Penempatan Driver Mandiri berhasil diperbarui',
                ]
            );

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $surat = SuratPenempatanDriverMandiriModel::findOrFail($id);
            $surat->delete();

            return redirect()->route('surat-penempatan-driver-mandiri.index')
                ->with('delete_success', 'Surat Penempatan Driver Mandiri berhasil dihapus');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generatePDF($id)
    {
        try {
            $surat = SuratPenempatanDriverMandiriModel::findOrFail($id);
            $relativePath = str_replace('/storage/', '', $surat->file_path_pdf);
            $filePath = storage_path('app/public/' . $relativePath);
            return response()->download($filePath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateWord($id)
    {
        try {
            $surat = SuratPenempatanDriverMandiriModel::findOrFail($id);
            $relativePath = str_replace('/storage/', '', $surat->file_path_docx);
            $filePath = storage_path('app/public/' . $relativePath);
            return response()->download($filePath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateFile(Request $request) {
        $apiURL = env('FLASK_API_URL') . '/generate/surat/penempatan/mandiri/driver';
        $responses = Http::post($apiURL, [
            'id_surat_penempatan' => $request->id,
        ]);

        $responsesData = $responses->json();

        if($responses->successful() && $responsesData['status'] == 'success') {
            return response()->json([
                'status' => 'success',
            ]);
        }
        return response()->json([
            'status' => 'error',
        ]);
    }
}
