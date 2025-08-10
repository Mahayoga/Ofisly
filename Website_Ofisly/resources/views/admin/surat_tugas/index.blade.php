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
                        <!-- Data akan diisi via AJAX -->
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
                <form id="createForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nama_kandidat" class="form-label">Nama Kandidat</label>
                                <input type="text" class="form-control" id="nama_kandidat" name="nama_kandidat" required>
                                <small class="text-muted">Masukkan nama lengkap karyawan</small>
                            </div>
                            <div class="col-md-6">
                                <label for="tgl_penugasan" class="form-label">Tanggal Penugasan</label>
                                <input type="date" class="form-control" id="tgl_penugasan" name="tgl_penugasan" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2

            // Inisialisasi DataTable
            $('#suratTugasTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('surat-tugas.data') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'no_surat',
                        name: 'no_surat'
                    },
                    {
                        data: 'nama_kandidat',
                        name: 'nama_kandidat'
                    },
                    {
                        data: 'tgl_penugasan',
                        name: 'tgl_penugasan',
                        render: function(data) {
                            return new Date(data).toLocaleDateString('id-ID');
                        }
                    },
                    {
                        data: 'tgl_surat_pembuatan',
                        name: 'tgl_surat_pembuatan',
                        render: function(data) {
                            return new Date(data).toLocaleDateString('id-ID');
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
                }
            });

            // Submit form
            $('#createForm').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('surat-tugas.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#createModal').modal('hide');
                        $('#suratTugasTable').DataTable().ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.success,
                            timer: 1500
                        });
                        $('#createForm')[0].reset();
                        $('.select2-karyawan').val(null).trigger('change');
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = '';

                        $.each(errors, function(key, value) {
                            errorMessage += value + '<br>';
                        });

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            html: errorMessage
                        });
                    }
                });
            });
        });
    </script>
@endsection
