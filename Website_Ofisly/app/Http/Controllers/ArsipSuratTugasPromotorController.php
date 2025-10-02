<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratTugasPromotor;

class ArsipSuratTugasPromotorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $arsipPromotor = SuratTugasPromotor::where('is_arsip', 1)->get();
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
     public function destroy(string $id)
    {
        $surat = SuratTugasPromotor::findOrFail($id);
        if($surat->file_path_docx && file_exists(public_path($surat->file_path_docx))){
            unlink(public_path($surat->file_path_docx));
        }
        if($surat->file_path_pdf && file_exists(public_path($surat->file_path_pdf))){
            unlink(public_path($surat->file_path_pdf));
        }

        $surat->delete();

        return redirect()->route('arsip.surat-tugas-promotor.index')->with('success', 'Data berhasil dihapus permanen.');
    }
}
