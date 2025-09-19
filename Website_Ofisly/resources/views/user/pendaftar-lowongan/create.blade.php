@extends('user.layouts.app')

@section('title', 'Form Pendaftaran')

@section('content')
<div class="container mt-5">

    {{-- Pesan sukses/gagal --}}
    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    @if(session('error'))
        <script>alert("{{ session('error') }}");</script>
    @endif

    {{-- Validasi error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
        </div>

        <button type="submit" class="btn btn-success">Kirim Pendaftaran</button>
    </form>
</div>
@endsection
