@extends('user.layout.app')
@section('title', $lowongan->judul)
@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        @if($lowongan->gambar)
            <img src="{{ asset('storage/' . $lowongan->gambar) }}" class="card-img-top" alt="{{ $lowongan->judul }}">
        @else
            <img src="https://via.placeholder.com/800x400" class="card-img-top" alt="default">
        @endif

        <div class="card-body">
            <h3 class="card-title">{{ $lowongan->judul }}</h3>
            <p class="card-text">{!! nl2br(e($lowongan->deskripsi)) !!}</p>
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('daftar-lowongan.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection
