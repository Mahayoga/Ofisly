{{-- @extends('admin.layout.index')
@section('title', 'Edit Surat Penempatan')

@section('content')
    <div class="card mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Edit Surat Penempatan</h6>
            <a href="{{ route('surat-penempatan-driver-mandiri.index') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form action="{{ route('surat-penempatan-driver-mandiri.update', $suratPenempatan->id_surat_penempatan) }}"
                method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label for="nomor_surat" class="form-label">Nomor Surat</label>
                        <input type="text" class="form-control" id="nomor_surat" name="nomor_surat"
                            value="{{ old('nomor_surat', $suratPenempatan->nomor_surat) }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nama_kandidat" class="form-label">Nama Kandidat</label>
                        <input type="text" class="form-control" id="nama_kandidat" name="nama_kandidat"
                            value="{{ old('nama_kandidat', $suratPenempatan->nama_kandidat) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="jabatan_kandidat" class="form-label">Jabatan Kandidat</label>
                        <input type="text" class="form-control" id="jabatan_kandidat" name="jabatan_kandidat"
                            value="{{ old('jabatan_kandidat', $suratPenempatan->jabatan_kandidat) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tgl_mulai_penempatan" class="form-label">Tanggal Penempatan</label>
                        <input type="date" class="form-control" id="tgl_mulai_penempatan" name="tgl_mulai_penempatan"
                            value="{{ old('tgl_mulai_penempatan', optional($suratPenempatan->tgl_mulai_penempatan)->format('Y-m-d')) }}"
                            required>
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection --}}
