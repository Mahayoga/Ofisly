<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratTugasMandiriModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PDF;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class SuratTugasMandiriController extends Controller
{
    public function index()
    {
        $suratPenempatan = SuratTugasMandiriModel::latest()->orderBy('created_at', 'desc')->get();
        return view('admin.surat-tugas-mandiri.index', compact('suratPenempatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kandidat' => 'required|string|max:255',
            'jabatan_kandidat' => 'required|string|max:255',
            'tgl_mulai_penempatan' => 'required|date',
        ]);

        try {
            $now = Carbon::now();
            $bulanRomawi = $this->convertToRoman($now->month); 
            $tahun = $now->year;
            $lastSurat = SuratTugasMandiriModel::whereYear('tgl_surat_pembuatan', $tahun)
                ->whereMonth('tgl_surat_pembuatan', $now->month)
                ->orderBy('id_surat_penempatan', 'desc')
                ->first();

            if ($lastSurat) {
                $lastNumber = (int) substr($lastSurat->nomor_surat, 0, 3);
                $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '001'; 
            }

            $nomorSurat = "{$newNumber}/PI-SBY/Mandiri/{$bulanRomawi}/{$tahun}";
            $resultCreate = SuratTugasMandiriModel::create([
                'nomor_surat' => $nomorSurat,
                'nama_kandidat' => $request->nama_kandidat,
                'jabatan_kandidat' => $request->jabatan_kandidat,
                'tgl_mulai_penempatan' => $request->tgl_mulai_penempatan,
                'tgl_surat_pembuatan' => $now->format('Y-m-d'),
            ]);

            return redirect()->route('surat-tugas-mandiri.index')
                ->with([
                    'success' => 'Surat Tugas Mandiri berhasil dibuat',
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

    private function convertToRoman($month)
    {
        $romawi = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $romawi[$month];
    }

    public function edit($id)
    {
        $suratPenempatan = SuratTugasMandiriModel::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => [
                'nomor_surat' => $suratPenempatan->nomor_surat,
                'nama_kandidat' => $suratPenempatan->nama_kandidat,
                'jabatan_kandidat' => $suratPenempatan->jabatan_kandidat,
                'tgl_surat_pembuatan' => $suratPenempatan->tgl_surat_pembuatan,
                'tgl_mulai_penempatan' => $suratPenempatan->tgl_mulai_penempatan,
                'file_path_docx' => $suratPenempatan->file_path_docx,
                'file_path_pdf' => $suratPenempatan->file_path_pdf,
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // 'nomor_surat' => 'required|string|max:30',
            'edit_nama_kandidat' => 'required|string|max:255',
            'edit_jabatan_kandidat' => 'required|string|max:255',
            'edit_tgl_mulai_penempatan' => 'required|date',
        ]);

        try {
            $suratPenempatan = SuratTugasMandiriModel::findOrFail($id);
            $suratPenempatan->update([
                // 'nomor_surat' => $request->nomor_surat,
                'nama_kandidat' => $request->edit_nama_kandidat,
                'jabatan_kandidat' => $request->edit_jabatan_kandidat,
                'tgl_mulai_penempatan' => $request->edit_tgl_mulai_penempatan,
                'tgl_surat_pembuatan' => Carbon::now()->format('Y-m-d'),
            ]);

            $pathDocx = $suratPenempatan->file_path_docx;
            $pathPDF = $suratPenempatan->file_path_pdf;
            if(is_file(public_path() . $pathDocx)) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $pathDocx));
            }
            if(is_file(public_path() . $pathPDF)) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $pathPDF));
            }

            return redirect()->route('surat-tugas-mandiri.index')
                ->with([
                    'success' => 'Surat Tugas berhasil di edit',
                    'action' => true,
                    'id_generate' => $suratPenempatan->id_surat_penempatan
                ]);

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
            $suratPenempatan = SuratTugasMandiriModel::findOrFail($id);
            $suratPenempatan->delete();

            if($suratPenempatan->delete()) {
                $pathDocx = $suratPenempatan->file_path_docx;
                $pathPDF = $suratPenempatan->file_path_pdf;
                if(is_file(public_path() . $pathDocx)) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $pathDocx));
                }
                if(is_file(public_path() . $pathPDF)) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $pathPDF));
                }
            }

            return redirect()->route('surat-tugas-mandiri.index')
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
            $surat = SuratTugasMandiriModel::findOrFail($id);
            $relativePath = str_replace('/storage/', '', $surat->file_path_pdf);
            $filePath = storage_path('app/public/' . $relativePath);
            return response()->download($filePath)->deleteFileAfterSend(false);

        } catch (\Exception $e) {
            abort(500, 'Hehe');
        }
    }

    public function generateWord($id)
    {
        try {
            $surat = SuratTugasMandiriModel::findOrFail($id);
            $relativePath = str_replace('/storage/', '', $surat->file_path_docx);
            $filePath = storage_path('app/public/' . $relativePath);
            return response()->download($filePath)->deleteFileAfterSend(false);

        } catch (\Exception $e) {
            abort(500, 'Hehe');
        }
    }

    public function generateFile(Request $request)
    {
        $apiURL = env('FLASK_API_URL') . '/generate/surat/tugas/mandiri';
        $model= new SuratTugasMandiriModel();
        $responses = Http::post($apiURL, [
            'id_surat_penempatan' => $request->id,
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
}
