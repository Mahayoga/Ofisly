@extends('user.layouts.app')

@section('title', 'Daftar Lowongan')

@section('content')
  <style>
    div.card-text.truncate-3 p {
      margin: 0;
    }

    .truncate-3 {
      display: -webkit-box;
      -webkit-line-clamp: 3;   /* tampilkan maksimal 3 baris */
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
  </style>
  <div class="container mt-5">
    <h2 class="mb-4 text-center">Daftar Lowongan Pekerjaan</h2>
    <div class="row">
      @foreach($lowongan as $item)
        <div class="col-md-4 mb-4">
          <div class="card h-100 shadow-sm">
            @if($item->gambar)
              <img src="{{ asset('storage/' . $item->gambar) }}" class="card-img-top" alt="{{ $item->judul }}"
                style="max-height: 200px; object-fit: cover;">
            @else
              <img src="https://via.placeholder.com/400x200" class="card-img-top" alt="default" style="max-height:200px; object-fit:cover;">
            @endif
            <div class="card-body">
              <h5 class="card-title">{{ $item->judul }}</h5>
              <div class="card-text truncate-3">{!! $item->deskripsi !!}</div>
              <a href="{{ route('daftar-lowongan.show', ['daftar_lowongan' => $item->id_lowongan_pekerjaan]) }}" class="btn btn-primary">Lihat detail</a>
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