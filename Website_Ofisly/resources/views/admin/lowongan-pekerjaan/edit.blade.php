@extends('admin.layout.app')
@section('title', 'Edit Lowongan Pekerjaan')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Lowongan Pekerjaan</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('lowongan-pekerjaan.update', $lowonganPekerjaan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="judul">Judul Lowongan</label>
                    <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $lowonganPekerjaan->judul) }}" required>
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi Lowongan</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror" required>{{ old('deskripsi', $lowonganPekerjaan->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="gambar">Gambar (Opsional)</label><br>
                    @if($lowonganPekerjaan->gambar)
                        <img src="{{ asset('storage/'.$lowonganPekerjaan->gambar) }}" alt="Gambar Lowongan" width="120" class="mb-2">
                    @endif
                    <input type="file" name="gambar" id="gambar" class="form-control-file @error('gambar') is-invalid @enderror" accept="image/*">
                    @error('gambar')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tanggal_post">Tanggal Post</label>
                    <input type="date" name="tanggal_post" id="tanggal_post" class="form-control @error('tanggal_post') is-invalid @enderror" value="{{ old('tanggal_post', $lowonganPekerjaan->tanggal_post->format('Y-m-d')) }}" required>
                    @error('tanggal_post')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">Update Lowongan</button>
                <a href="{{ route('lowongan-pekerjaan.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
