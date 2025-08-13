@extends('admin.layout.app')
@section('title', 'Detail Lowongan Pekerjaan')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Detail Lowongan Pekerjaan</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <h3>{{ $lowonganPekerjaan->judul }}</h3>
            <p><strong>Tanggal Post:</strong> {{ \Carbon\Carbon::parse($lowonganPekerjaan->tanggal_post)->format('d F Y') }}</p>
            
            @if($lowonganPekerjaan->gambar)
                <img src="{{ asset('storage/'.$lowonganPekerjaan->gambar) }}" alt="{{ $lowonganPekerjaan->judul }}" width="300" class="mb-3">
            @endif

            <p>{!! nl2br(e($lowonganPekerjaan->deskripsi)) !!}</p>

            <a href="{{ route('lowongan-pekerjaan.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </div>
    </div>
</div>
@endsection
