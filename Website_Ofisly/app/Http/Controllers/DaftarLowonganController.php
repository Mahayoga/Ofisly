<?php

namespace App\Http\Controllers;

use App\Models\LowonganPekerjaanModel;

class DaftarLowonganController extends Controller
{
    public function index()
    {
        $lowongan = LowonganPekerjaanModel::latest()->paginate(6); 
        return view('daftar-lowongan.index', compact('lowongan'));
    }

    public function show($id_lowongan_pekerjaan)
{
    $lowongan = LowonganPekerjaanModel::findOrFail($id_lowongan_pekerjaan);
    return view('daftar-lowongan.show', compact('lowongan'));
}

}
