@extends('user.layouts.app')

@section('title', 'Form Pendaftaran')

@section('content')
<div class="container mt-5">

    <h3>Form Pendaftaran - {{ $lowongan->judul }}</h3>

    <form action="{{ route('daftar-lowongan.store', $lowongan->id_lowongan_pekerjaan) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" name="nama" value="{{ old('nama') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">No. Telepon</label>
            <input type="text" class="form-control" name="no_telp" value="{{ old('no_telp') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Upload CV</label>
            <input type="file" class="form-control" name="cv" required>
            <small class="text-muted">Maksimal ukuran CV 2MB</small>
        </div>

        <button type="submit" class="btn btn-success">Kirim Pendaftaran</button>
    </form>
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
          title: "Pendaftaran",
          text: "{{ session('error') }}",
          icon: "error"
          });
        });
        </script>
    @endif
@endsection