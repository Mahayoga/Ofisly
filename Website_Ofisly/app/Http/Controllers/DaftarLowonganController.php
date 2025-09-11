<?php

namespace App\Http\Controllers;

use App\Models\LowonganPekerjaanModel;
use App\Models\PendaftarLowonganModel;
use Illuminate\Http\Request;

class DaftarLowonganController extends Controller
{
    public function index()
    {
        $lowongan = LowonganPekerjaanModel::latest()->paginate(6); 
        return view('user.daftar-lowongan.index', compact('lowongan'));
    }

    public function show($id_lowongan_pekerjaan)
    {
        $lowongan = LowonganPekerjaanModel::findOrFail($id_lowongan_pekerjaan);
        return view('user.daftar-lowongan.show', compact('lowongan'));
    }

    public function create($id_lowongan_pekerjaan)
    {
        $lowongan = LowonganPekerjaanModel::findOrFail($id_lowongan_pekerjaan);
        return view('user.pendaftar-lowongan.create', compact('lowongan'));
    }

    public function store(Request $request, $id_lowongan_pekerjaan)
    {
        $lowongan = LowonganPekerjaanModel::findOrFail($id_lowongan_pekerjaan);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_telp' => 'nullable|string|max:20',
            'cv' => 'required|mimes:pdf,doc,docx|max:2048',
        ]);

        $cvPath = $request->file('cv')->store('cv', 'public');

        PendaftarLowonganModel::create([
            'id_lowongan_pekerjaan' => $lowongan->id_lowongan_pekerjaan,
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'no_telp' => $validated['no_telp'],
            'cv' => $cvPath,
            'status' => 'Pending',
        ]);

        return redirect()->route('daftar-lowongan.show', $id_lowongan_pekerjaan)
                         ->with('success', 'Pendaftaran berhasil dikirim.');
    }
}
