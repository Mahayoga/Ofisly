<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratTugasPenggantiDriverModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PDF;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class SuratTugasPenggantiDriverController extends Controller
{

    public function fetchRowData() {
        $suratTugas = SuratTugasPenggantiDriverModel::latest()->get();
        // dd($suratTugas->toArray());
        // return response()->json([
        //     'status' => true,
        //     'data' => $suratTugas
        // ]);
        return response()->json([
            'status' => true,
            'data' => $suratTugas->toArray()
        ]);
    }

    public function index()
    {
        $suratTugas = SuratTugasPenggantiDriverModel::where('is_arsip', 0)->latest()->get();
        return view('admin.surat_tugas.index', compact('suratTugas'));
    }

    public function show($id)
    {

    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kandidat' => 'required|string|max:255',
            "nik_kandidat" => "required|string|max:16",
            "jabatan_kandidat" => "required|string|max:255",
            "nama_pengganti_kandidat" => "required|string|max:255",
            "daerah_penempatan" => "required|string|max:255",
            "tgl_mulai_penugasan" => "required|date",
            "tgl_selesai_penugasan" => "required|date",
        ]);

        try {
            // $count = SuratTugasPenggantiDriverModel::count() + 1;
            // $no_surat = 'ST/' . date('Y') . '/' . str_pad($count, 4, '0', STR_PAD_LEFT);

            /**
             * $table->uuid('id_surat_tugas')->primary();
             * $table->string('no_surat');                  //NOPE
             * $table->string('nama_kandidat');
             * $table->char('nik_kandidat', 16);
             * $table->string('jabatan_kandidat');
             * $table->string('nama_pengganti_kandidat');
             * $table->date('tgl_mulai_penugasan');
             * $table->date('tgl_selesai_penugasan');
             * $table->date('tgl_surat_pembuatan');
             * $table->string('status')->nullable();        //??
             * $table->string('created_by')->nullable();    //??
             * $table->string('file_path')->nullable();     //Optional
             */



            $resultCreate = SuratTugasPenggantiDriverModel::create([
                'nama_kandidat' => $request->nama_kandidat,
                'nik_kandidat' => $request->nik_kandidat,
                'jabatan_kandidat' => $request->jabatan_kandidat,
                'nama_pengganti_kandidat' => $request->nama_pengganti_kandidat,
                'daerah_penempatan' => $request->daerah_penempatan,
                'tgl_mulai_penugasan' => $request->tgl_mulai_penugasan,
                'tgl_selesai_penugasan' => $request->tgl_selesai_penugasan,
                'tgl_surat_pembuatan' => Carbon::now()->format('Y-m-d'),
            ]);

            // return redirect()->route('surat-tugas.index')
            //     ->with([
            //         'success' => 'Surat Tugas berhasil dibuat',
            //         'action' => true,
            //         'id_generate' => $resultCreate->id_surat_tugas
            //     ]);
            return response()->json([
                'status' => true,
                'action' => true,
                'id_generate' => $resultCreate->id_surat_tugas
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
        $surat = SuratTugasPenggantiDriverModel::findOrFail($id);
        // $tglPenugasan = (string) $surat->tgl_penugasan;
        return response()->json([
            'success' => true,
            'data' => [
                'nama_kandidat' => $surat->nama_kandidat,
                'nik_kandidat' => $surat->nik_kandidat,
                'jabatan_kandidat' => $surat->jabatan_kandidat,
                'nama_pengganti_kandidat' => $surat->nama_pengganti_kandidat,
                'daerah_penempatan' => $surat->daerah_penempatan,
                'tgl_mulai_penugasan' => $surat->tgl_mulai_penugasan,
                'tgl_selesai_penugasan' => $surat->tgl_selesai_penugasan,
                'tgl_surat_pembuatan' => $surat->tgl_surat_pembuatan,
                'status' => $surat->status,
                'created_by' => $surat->created_by,
                'file_path_docx' => $surat->file_path_docx,
                'file_path_pdf' => $surat->file_path_pdf,
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'edit_nama_kandidat' => 'required|string|max:255',
            "edit_nik_kandidat" => "required|string|max:16",
            "edit_jabatan_kandidat" => "required|string|max:255",
            'edit_daerah_penempatan' => "required|string|max:255",
            "edit_nama_pengganti_kandidat" => "required|string|max:255",
            "edit_tgl_mulai_penugasan" => "required|date",
            "edit_tgl_selesai_penugasan" => "required|date",
        ]);


        try {
            $surat = SuratTugasPenggantiDriverModel::findOrFail($id);
            // dd($surat);
            $surat->update([
                'nama_kandidat' => $request->edit_nama_kandidat,
                'nik_kandidat' => $request->edit_nik_kandidat,
                'jabatan_kandidat' => $request->edit_jabatan_kandidat,
                'nama_pengganti_kandidat' => $request->edit_nama_pengganti_kandidat,
                'daerah_penempatan' => $request->edit_daerah_penempatan,
                'tgl_mulai_penugasan' => $request->edit_tgl_mulai_penugasan,
                'tgl_selesai_penugasan' => $request->edit_tgl_selesai_penugasan,
                'tgl_surat_pembuatan' => Carbon::now()->format('Y-m-d'),
            ]);
            
            $pathDocx = $surat->file_path_docx;
            $pathPDF = $surat->file_path_pdf;
            if(is_file(public_path() . $pathDocx)) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $pathDocx));
            }
            if(is_file(public_path() . $pathPDF)) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $pathPDF));
            }

            // return redirect()->route('surat-tugas.index')
            //     ->with([
            //         'success' => 'Surat Tugas berhasil di edit, sabar ya masih di generate ulang!',
            //         'action' => true,
            //         'id_generate' => $surat->id_surat_tugas
            //     ]);

            return response()->json([
                'status' => true,
                'action' => true,
                'id_generate' => $surat->id_surat_tugas
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
        $surat = SuratTugasPenggantiDriverModel::findOrFail($id);
        $surat->is_arsip = 1;
        $surat->save();

        return redirect()->route('surat-tugas.index')
            ->with('success', 'Surat Tugas berhasil dipindahkan ke arsip.');

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
            $surat = SuratTugasPenggantiDriverModel::findOrFail($id);
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
            $surat = SuratTugasPenggantiDriverModel::findOrFail($id);
            $relativePath = str_replace('/storage/', '', $surat->file_path_docx);
            $filePath = storage_path('app/public/' . $relativePath);
            return response()->download($filePath)->deleteFileAfterSend(false);

        } catch (\Exception $e) {
            abort(500, 'Hehe');
        }
    }

    public function generateFile(Request $request) {
        $apiURL = env('FLASK_API_URL') . '/generate/surat/penggati/driver';
        $model = new SuratTugasPenggantiDriverModel();
        $responses = Http::post($apiURL, [
            'id_surat_tugas' => $request->id,
            'table' => $model->getTable(),

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

    public function fileCheck($id, $type) {
        $apiURL = env('FLASK_API_URL') . '/check/generate/run';
        $dataSurat = SuratTugasPenggantiDriverModel::findOrFail($id);
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
