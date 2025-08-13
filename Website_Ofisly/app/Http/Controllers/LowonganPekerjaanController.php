<?php

namespace App\Http\Controllers;
use App\Models\LowonganPekerjaanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LowonganPekerjaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lowonganPekerjaan = LowonganPekerjaanModel::latest()->get();
        return view('admin.lowongan-pekerjaan.index', compact('lowonganPekerjaan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.lowongan-pekerjaan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_post' => 'required|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('lowongan-images', 'public');
            $validated['gambar'] = $path;
        }

        LowonganPekerjaanModel::create($validated);
        return redirect()->route('lowongan-pekerjaan.index')->with('success', 'Lowongan pekerjaan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lowonganPekerjaan = LowonganPekerjaanModel::findOrFail($id);
        return view('admin.lowongan-pekerjaan.show', compact('lowonganPekerjaan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lowonganPekerjaan = LowonganPekerjaanModel::findOrFail($id);
        return view('admin.lowongan-pekerjaan.edit', compact('lowonganPekerjaan'));      
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lowonganPekerjaan = LowonganPekerjaanModel::findOrFail($id);
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_post' => 'required|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);


        if ($request->hasFile('gambar')) {
            if ($lowonganPekerjaan->gambar && Storage::disk('public')->exists($lowonganPekerjaan->gambar)) {
                Storage::disk('public')->delete($lowonganPekerjaan->gambar);
            }
            $path = $request->file('gambar')->store('lowongan-images', 'public');
            $validated['gambar'] = $path;
    }

            $lowonganPekerjaan->update($validated);
            return redirect()->route('lowongan-pekerjaan.index')->with('success', 'Lowongan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lowonganPekerjaan = LowonganPekerjaanModel::findOrFail($id);
    
        if ($lowonganPekerjaan->gambar && Storage::disk('public')->exists($lowonganPekerjaan->gambar)) {
            Storage::disk('public')->delete($lowonganPekerjaan->gambar);
    }

    $lowonganPekerjaan->delete();

    return redirect()->route('lowongan-pekerjaan.index')->with('success', 'Lowongan berhasil dihapus.');
    }
}
