<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratTugasModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use PDF;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class SuratTugasPenggantiDriverController extends Controller
{
    public function index()
    {
        $suratTugas = SuratTugasModel::latest()->get();
        return view('admin.surat_tugas.index', compact('suratTugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kandidat' => 'required|string|max:255',
            'tgl_penugasan' => 'required|date|after_or_equal:today',
        ], [
            'tgl_penugasan.after_or_equal' => 'Tanggal penugasan harus hari ini atau setelahnya'
        ]);

        try {
            $count = SuratTugasModel::count() + 1;
            $no_surat = 'ST/' . date('Y') . '/' . str_pad($count, 4, '0', STR_PAD_LEFT);

            SuratTugasModel::create([
                'no_surat' => $no_surat,
                'nama_kandidat' => $request->nama_kandidat,
                'tgl_penugasan' => $request->tgl_penugasan,
                'tgl_surat_pembuatan' => Carbon::now()->format('Y-m-d'),
                'created_by' => auth()->id(),
            ]);

            // return redirect()->route('surat-tugas.index')
            //     ->with('success', 'Surat Tugas berhasil dibuat');
            return response()->json([
                'success' => true,
                'message' => 'Surat Tugas berhasil dibuat',
                'data' => [
                    'no_surat' => $no_surat,
                    'nama_kandidat' => $request->nama_kandidat,
                    'tgl_penugasan' => Carbon::parse($request->tgl_penugasan)->format('d/m/Y'),
                    'tgl_surat_pembuatan' => Carbon::now()->format('d/m/Y')
                ]
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
        $surat = SuratTugasModel::findOrFail($id);
        $tglPenugasan = (string) $surat->tgl_penugasan;
        return response()->json([
            'success' => true,
            'data' => [
                'id_surat_tugas' => $surat->id_surat_tugas,
                'no_surat' => $surat->no_surat,
                'nama_kandidat' => $surat->nama_kandidat,
                'tgl_penugasan' => explode(' ', $tglPenugasan)[0],
                'tgl_surat_pembuatan' => $surat->tgl_surat_pembuatan
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kandidat' => 'required|string|max:255',
            'tgl_penugasan' => 'required|date',
        ]);

        try {
            $surat = SuratTugasModel::findOrFail($id);
            $surat->update([
                'nama_kandidat' => $request->nama_kandidat,
                'tgl_penugasan' => $request->tgl_penugasan,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Surat Tugas berhasil diperbarui',
                'updatedData' => $surat->fresh(),
                'formattedDates' => [
                    'tgl_penugasan' => Carbon::parse($surat->tgl_penugasan)->format('d/m/Y'),
                    'tgl_surat_pembuatan' => Carbon::parse($surat->tgl_surat_pembuatan)->format('d/m/Y')
                ]
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
            $surat = SuratTugasModel::findOrFail($id);
            $surat->delete();

            return response()->json([
                'success' => true,
                'message' => 'Surat Tugas berhasil dihapus'
            ]);

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
            $surat = SuratTugasModel::findOrFail($id);

            $fileName = 'surat_tugas_' . str_replace('/', '-', $surat->no_surat) . '.pdf';

            $pdf = PDF::loadView('admin.surat_tugas.template', compact('surat'));

            return $pdf->stream($fileName);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateWord($id)
    {
        dd($id);
        try {
            $apiURL = env('FLASK_API_URL') . '/generate/surat/penggati/driver';
            $responses = Http::post($apiURL, [
                'id_surat_tugas' => $id
            ]);
            $responsesData = $responses->json();
            if($responses->successful() && $responsesData['status'] == 'success') {
                return response()->json([
                    'status' => 'success',
                    'result' => $responsesData['result'],
                    'data' => $responsesData['data']
                ]);
            }

            return response()->download('C:\\Users\\myoga\\Documents\\Politeknik Negeri Jember\\Mahayoga Semester 5\\Project Magang\\Ofisly\\Website_Ofisly\\public\\storage\\uploads\\surat_template\\template.docx', 'template.docx')->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
