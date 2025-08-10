<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratTugasModel;
use Carbon\Carbon;
use PDF;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class SuratTugasController extends Controller
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

            return redirect()->route('surat-tugas.index')
                ->with('success', 'Surat Tugas berhasil dibuat');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $surat = SuratTugasModel::findOrFail($id);
        return view('admin.surat_tugas.edit', compact('surat'));
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

            return redirect()->route('surat-tugas.index')
                ->with('success', 'Surat Tugas berhasil diperbarui');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $surat = SuratTugasModel::findOrFail($id);
            $surat->delete();

            return redirect()->route('surat-tugas.index')
                ->with('success', 'Surat Tugas berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
            return redirect()->back()
                ->with('error', 'Gagal menghasilkan PDF: ' . $e->getMessage());
        }
    }

    public function generateWord($id)
    {
        try {
            $surat = SuratTugasModel::findOrFail($id);
            $phpWord = new PhpWord();

            $section = $phpWord->addSection();

            $titleStyle = ['bold' => true, 'size' => 16];
            $center = ['alignment' => 'center'];

            $section->addText('SURAT TUGAS', $titleStyle, $center);
            $section->addText($surat->no_surat, ['bold' => true], $center);
            $section->addTextBreak(2);

            $section->addText('Yang bertanda tangan di bawah ini, memberikan tugas kepada:');
            $section->addText('Nama: ' . $surat->nama_kandidat);
            $section->addText('Tanggal Penugasan: ' . Carbon::parse($surat->tgl_penugasan)->format('d F Y'));
            $section->addTextBreak(2);
            $section->addText('Demikian surat tugas ini dibuat untuk dapat dipergunakan sebagaimana mestinya.');
            $section->addTextBreak(2);

            $footer = $section->addTextRun(['alignment' => 'right']);
            $footer->addText('Jakarta, ' . Carbon::parse($surat->tgl_surat_pembuatan)->format('d F Y'));
            $footer->addTextBreak(3);
            $footer->addText('(_______________________)');

            $fileName = 'surat_tugas_' . str_replace('/', '-', $surat->no_surat) . '.docx';
            $tempFile = tempnam(sys_get_temp_dir(), 'surat_tugas_') . '.docx';

            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save($tempFile);

            return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghasilkan Word: ' . $e->getMessage());
        }
    }
}
