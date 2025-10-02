@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Surat Penempatan Pramubakti Mandiri</h4>

    {{-- Tabel Data --}}
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Surat Penempatan Pramubakti</span>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-circle"></i> Tambah Surat
            </button>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nomor Surat</th>
                        <th>Nama Pramubakti</th>
                        <th>Tanggal Surat</th>
                        <th>File</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($surat as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nomor_surat }}</td>
                        <td>{{ $item->nama_pramubakti }}</td>
                        <td>{{ $item->tgl_surat }}</td>
                        <td>
                            @if($item->file_path_pdf)
                                <a href="{{ route('surat-penempatan-pramubakti-mandiri.download', $item->id) }}" 
                                   class="btn btn-sm btn-success">
                                    <i class="bi bi-file-earmark-pdf"></i> Unduh
                                </a>
                            @else
                                <span class="badge bg-secondary">Belum ada file</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('surat-penempatan-pramubakti-mandiri.edit', $item->id) }}" 
                               class="btn btn-sm btn-warning">
                               <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <form action="{{ route('surat-penempatan-pramubakti-mandiri.destroy', $item->id) }}" 
                                  method="POST" style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data ini?')">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data surat</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Tambah Surat --}}
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('surat-penempatan-pramubakti-mandiri.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Surat Penempatan Pramubakti Mandiri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nomor Surat</label>
                        <input type="text" name="nomor_surat" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Nama Pramubakti</label>
                        <input type="text" name="nama_pramubakti" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal Surat</label>
                        <input type="date" name="tgl_surat" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Upload File (PDF)</label>
                        <input type="file" name="file_pdf" class="form-control" accept="application/pdf">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
