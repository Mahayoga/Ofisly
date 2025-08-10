@extends('admin.layout.index')
@section('title', 'Surat Tugas')

@section('content')
    <div class="card mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Surat Tugas</h6>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fas fa-plus"></i> Tambah Surat
            </button>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="suratTugasTable" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th>No. Surat</th>
                            <th>Nama Kandidat</th>
                            <th>Tanggal Penugasan</th>
                            <th>Tanggal Pembuatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suratTugas as $surat)
                        <tr>
                            <td>{{ $surat->no_surat }}</td>
                            <td>{{ $surat->nama_kandidat }}</td>
                            <td>{{ \Carbon\Carbon::parse($surat->tgl_penugasan)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($surat->tgl_surat_pembuatan)->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('surat-tugas.generate-pdf', $surat->id_surat_tugas) }}"
                                       class="btn btn-sm btn-danger" target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <a href="{{ route('surat-tugas.generate-word', $surat->id_surat_tugas) }}"
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-file-word"></i>
                                    </a>
                                    <a href="{{ route('surat-tugas.edit', $surat->id_surat_tugas) }}"
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('surat-tugas.destroy', $surat->id_surat_tugas) }}"
                                          method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createModalLabel">Buat Surat Tugas Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('surat-tugas.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nama_kandidat" class="form-label">Nama Kandidat</label>
                                <input type="text" class="form-control" id="nama_kandidat" name="nama_kandidat" required>
                            </div>
                            <div class="col-md-6">
                                <label for="tgl_penugasan" class="form-label">Tanggal Penugasan</label>
                                <input type="date" class="form-control" id="tgl_penugasan" name="tgl_penugasan" required
                                       min="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Inisialisasi DataTable tanpa server-side processing
        $(document).ready(function() {
            $('#suratTugasTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
                },
                columnDefs: [
                    { orderable: false, targets: 4 } // Kolom aksi tidak diurutkan
                ]
            });
        });
    </script>
@endsection
