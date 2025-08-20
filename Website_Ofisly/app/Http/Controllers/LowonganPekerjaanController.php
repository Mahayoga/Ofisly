<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LowonganPekerjaanModel;
use Illuminate\Support\Facades\Storage;

class LowonganPekerjaanController extends Controller
{
    public function index()
    {
        $lowonganPekerjaan = LowonganPekerjaanModel::latest()->get();
        return view('admin.lowongan-pekerjaan.index', compact('lowonganPekerjaan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'gambar.max' => 'Ukuran file tidak boleh lebih dari 2MB',
            'gambar.mimes' => 'Format file harus jpg, jpeg, atau png',
        ]);

        try {
            $data = [
                'judul'        => $request->judul,
                'deskripsi'    => $request->deskripsi,
                'tanggal_post' => now(), 
            ];

            if ($request->hasFile('gambar')) {
                $path = $request->file('gambar')->store('lowongan-images', 'public');
                $data['gambar'] = $path;
            }

            LowonganPekerjaanModel::create($data);

            return redirect()->route('lowongan-pekerjaan.index')
                ->with([
                    'success' => 'Lowongan pekerjaan berhasil ditambahkan',
                    'action'  => true,
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
        $lowonganPekerjaan = LowonganPekerjaanModel::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id'           => $lowonganPekerjaan->id_lowongan_pekerjaan,
                'judul'        => $lowonganPekerjaan->judul,
                'deskripsi'    => $lowonganPekerjaan->deskripsi,
                'tanggal_post' => $lowonganPekerjaan->tanggal_post,
                'gambar'       => $lowonganPekerjaan->gambar,
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'edit_judul'     => 'required|string|max:255',
            'edit_deskripsi' => 'required|string',
            'edit_gambar'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'edit_gambar.max'   => 'Ukuran file tidak boleh lebih dari 2MB',
            'edit_gambar.mimes' => 'Format file harus jpg, jpeg, atau png',
        ]);

        try {
            $lowonganPekerjaan = LowonganPekerjaanModel::findOrFail($id);

            $data = [
                'judul'     => $request->edit_judul,
                'deskripsi' => $request->edit_deskripsi,
            ];

            if ($request->hasFile('edit_gambar')) {
                if ($lowonganPekerjaan->gambar && Storage::disk('public')->exists($lowonganPekerjaan->gambar)) {
                    Storage::disk('public')->delete($lowonganPekerjaan->gambar);
                }
                $path = $request->file('edit_gambar')->store('lowongan-images', 'public');
                $data['gambar'] = $path;
            }

            $lowonganPekerjaan->update($data);

            return redirect()->route('lowongan-pekerjaan.index')
                ->with([
                    'success' => 'Lowongan pekerjaan berhasil diperbarui',
                    'action'  => true,
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
            $lowonganPekerjaan = LowonganPekerjaanModel::findOrFail($id);

            if ($lowonganPekerjaan->gambar && Storage::disk('public')->exists($lowonganPekerjaan->gambar)) {
                Storage::disk('public')->delete($lowonganPekerjaan->gambar);
            }

            $lowonganPekerjaan->delete();

            return redirect()->route('lowongan-pekerjaan.index')
                ->with('delete_success', 'Lowongan pekerjaan berhasil dihapus');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
