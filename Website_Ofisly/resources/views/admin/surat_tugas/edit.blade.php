@extends('admin.layout.index')
@section('title', 'Edit Surat Tugas')

@section('content')
    <div class="card mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Edit Surat Tugas</h6>
            <a href="{{ route('surat-tugas.index') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('surat-tugas.update', $surat->id_surat_tugas) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="no_surat" class="form-label">Nomor Surat</label>
                        <input type="text" class="form-control" id="no_surat" value="{{ $surat->no_surat }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="nama_kandidat" class="form-label">Nama Kandidat</label>
                        <input type="text" class="form-control" id="nama_kandidat" name="nama_kandidat"
                               value="{{ $surat->nama_kandidat }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tgl_penugasan" class="form-label">Tanggal Penugasan</label>
                        <input type="date" class="form-control" id="tgl_penugasan" name="tgl_penugasan"
                               value="{{ $surat->tgl_penugasan->format('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
