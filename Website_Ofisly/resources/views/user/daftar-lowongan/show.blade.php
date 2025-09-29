@extends('user.layouts.app')

@section('title', $lowongan->judul)

@section('content')
  <style>
    div.card-text p {
      margin: 0;
    }
  </style>

  <div class="container mt-5">
    <div class="mb-3">
      <a href="{{ route('daftar-lowongan.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
      </a>
    </div>

    <div class="card shadow-sm p-3">
      <div class="row g-0 align-items-start">
        <div class="col-md-5">
          @if($lowongan->gambar)
            <img src="{{ asset('storage/' . $lowongan->gambar) }}" alt="{{ $lowongan->judul }}" class="img-fluid rounded" style="max-width:100%; max-height:500px; object-fit:contain;">
          @else
            <img src="https://via.placeholder.com/600x800" alt="default" class="img-fluid rounded" style="max-width:100%; max-height:500px; object-fit:contain;">
          @endif
        </div>
        <div class="col-md-7">
          <div class="card-body p-2"> 
            <h3 class="card-title">{{ $lowongan->judul }}</h3>
            <div class="card-text mb-3">{!! $lowongan->deskripsi !!}</div>
            <a href="{{ route('daftar-lowongan.create', $lowongan->id_lowongan_pekerjaan) }}" class="btn btn-primary">
            <i class="bi bi-pencil-square"></i> Daftar
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
    @if(session('success'))
        <script>
        $(document).ready(function(){
          Swal.fire({
          title: "Pendaftaran",
          text: "{{ session('success') }}",
          icon: "success"
          });
        });
        </script>
    @endif

    @if(session('error'))
        <script>
        $(document).ready(function(){
          Swal.fire({
            title: "Peringatan",
            text: "{{ session('error') }}",
            icon: "error"
          });
        });
        </script>
    @endif
@endsection