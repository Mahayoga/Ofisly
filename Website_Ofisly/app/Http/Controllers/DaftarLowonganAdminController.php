<?php

namespace App\Http\Controllers;

use App\Models\PendaftarLowonganModel;
use Illuminate\Http\Request;

class DaftarLowonganAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pendaftar = PendaftarLowonganModel::all();
        // dd($pendaftar);
        return view('admin.pendaftar-lowongan.index', compact(['pendaftar']));
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
        $pendaftar = PendaftarLowonganModel::findOrFail($id);

        return response()->json([
            'status' => true,
            'dataPendaftar' => $pendaftar,
            'dataLowongan' => $pendaftar->lowongan
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'status_pendaftaran' => 'required'
        ]);

        $pendaftar = PendaftarLowonganModel::findOrFail($id);
        $pendaftar->update([
            'status' => $request->status_pendaftaran
        ]);

        return redirect()->route('pendaftar-lowongan.index')->with(['success' => 'Data Berhasil Disimpan']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
