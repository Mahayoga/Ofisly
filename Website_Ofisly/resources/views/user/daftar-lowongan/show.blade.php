@extends('user.layouts.app')

@section('title', $lowongan->judul)

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm p-3">
        <div class="row g-0 align-items-start">
            <!-- Bagian Gambar -->
            <div class="col-md-5">
                @if($lowongan->gambar)
                    <img src="{{ asset('storage/' . $lowongan->gambar) }}" 
                         alt="{{ $lowongan->judul }}" 
                         class="img-fluid rounded"
                         style="max-width:100%; max-height:500px; object-fit:contain;">
                @else
                    <img src="https://via.placeholder.com/600x800" 
                         alt="default"
                         class="img-fluid rounded"
                         style="max-width:100%; max-height:500px; object-fit:contain;">
                @endif
            </div>

            <!-- Bagian Deskripsi -->
            <div class="col-md-7 ps-3">
                <div class="card-body">
                    <h3 class="card-title">{{ $lowongan->judul }}</h3>
                    <p class="card-text">{!! nl2br(e($lowongan->deskripsi)) !!}</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="card-footer text-end">
            <a href="{{ route('daftar-lowongan.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection
