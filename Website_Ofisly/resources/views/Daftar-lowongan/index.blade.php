@extends('layout.app')


@section('title', 'Daftar Lowongan')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">Daftar Lowongan Pekerjaan</h2>
    <div class="row">
        @foreach($lowongan as $item)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                @if($item->gambar)
                    <img src="{{ asset('storage/' . $item->gambar) }}" class="card-img-top" alt="{{ $item->judul }}">
                @else
                    <img src="https://via.placeholder.com/400x200" class="card-img-top" alt="default">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $item->judul }}</h5>
                    <p class="card-text text-truncate">{{ $item->deskripsi }}</p>
                    <a href="{{ route('daftar-lowongan.show', $item->id_lowongan_pekerjaan) }}" class="btn btn-primary">Selengkapnya</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $lowongan->links() }}
    </div>
</div>
@endsection
