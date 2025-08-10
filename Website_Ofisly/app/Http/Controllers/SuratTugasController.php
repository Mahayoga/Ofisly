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
        return view('admin.surat_tugas.index');
    }

    public function getData()
    {
        $suratTugas = SuratTugasModel::orderBy('id_surat_tugas', 'desc')->get();
        return datatables()->of($suratTugas)
            ->addColumn('action', function($row) {
                $btn = '<div class="btn-group">
                    <a href="'.route('surat-tugas.generate-pdf', $row->id_surat_tugas).'" class="btn btn-sm btn-danger" target="_blank"><i class="fas fa-file-pdf"></i></a>
                    <a href="'.route('surat-tugas.generate-word', $row->id_surat_tugas).'" class="btn btn-sm btn-primary"><i class="fas fa-file-word"></i></a>
                    <button class="btn btn-sm btn-info edit-btn" data-id="'.$row->id_surat_tugas.'"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id_surat_tugas.'"><i class="fas fa-trash"></i></button>
                </div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kandidat' => 'required',
            'tgl_penugasan' => 'required|date',
        ]);

        // Generate nomor surat otomatis
        $count = SuratTugasModel::count() + 1;
        $no_surat = 'ST/' . date('Y') . '/' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $surat = SuratTugasModel::create([
            'no_surat' => $no_surat,
            'nama_kandidat' => $request->nama_kandidat,
            'tgl_penugasan' => $request->tgl_penugasan,
            'tgl_surat_pembuatan' => Carbon::now()->format('Y-m-d'),
        ]);

        return response()->json(['success' => 'Surat Tugas berhasil dibuat']);
    }

    public function generatePDF($id)
    {
        $surat = SuratTugasModel::findOrFail($id);

        $pdf = PDF::loadView('admin.surat_tugas.template', compact('surat'));

        return $pdf->stream('surat_tugas_'.$surat->no_surat.'.pdf');
    }

    public function generateWord($id)
    {
        $surat = SuratTugasModel::findOrFail($id);
        $phpWord = new PhpWord();

        $section = $phpWord->addSection();

        // Tambahkan konten surat
        $section->addText('SURAT TUGAS', ['bold' => true, 'size' => 16], ['alignment' => 'center']);
        $section->addText($surat->no_surat, ['bold' => true], ['alignment' => 'center']);
        $section->addTextBreak(2);

        $section->addText('Dengan ini menugaskan kepada:');
        $section->addText('Nama: ' . $surat->nama_kandidat);
        $section->addText('Tanggal Penugasan: ' . $surat->tgl_penugasan);
        $section->addTextBreak(2);
        $section->addText('Demikian surat tugas ini dibuat untuk dapat dipergunakan sebagaimana mestinya.');

        $fileName = 'surat_tugas_'.$surat->no_surat.'.docx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}

// class SuratTugasController extends Controller
// {
//     /**
//      * Display a listing of the resource.
//      */
//     public function index()
//     {
//         return view('admin.surat_tugas.index');
//     }

//     /**
//      * Show the form for creating a new resource.
//      */
//     public function create()
//     {
//         //
//     }

//     /**
//      * Store a newly created resource in storage.
//      */
//     public function store(Request $request)
//     {
//         //
//     }

//     /**
//      * Display the specified resource.
//      */
//     public function show(string $id)
//     {
//         //
//     }

//     /**
//      * Show the form for editing the specified resource.
//      */
//     public function edit(string $id)
//     {
//         //
//     }

//     /**
//      * Update the specified resource in storage.
//      */
//     public function update(Request $request, string $id)
//     {
//         //
//     }

//     /**
//      * Remove the specified resource from storage.
//      */
//     public function destroy(string $id)
//     {
//         //
//     }
// }
