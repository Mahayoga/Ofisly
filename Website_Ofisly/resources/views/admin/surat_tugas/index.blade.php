@extends('admin.layout.index')
@section('title', 'Surat Tugas')
@section('content')
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Data Surat Tugas</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Kumpulan data surat tugas</li>
            </ol>
        </div>
        <div class="card mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus"></i> Tambah Surat
                </button>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
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
                            @foreach ($suratTugas as $surat)
                                <tr data-id="{{ $surat->id_surat_tugas }}">
                                    <td>{{ $surat->no_surat }}</td>
                                    <td class="nama-kandidat">{{ $surat->nama_kandidat }}</td>
                                    <td class="tgl-penugasan">
                                        {{ \Carbon\Carbon::parse($surat->tgl_penugasan)->format('d/m/Y') }}</td>
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
                                            <button class="btn btn-sm btn-info edit-btn" data-bs-toggle="modal"
                                                data-bs-target="#editModal" data-id="{{ $surat->id_surat_tugas }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $surat->id_surat_tugas }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
                    <form id="createForm" action="{{ route('surat-tugas.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nama_kandidat" class="form-label">Nama Kandidat</label>
                                    <input type="text" class="form-control" id="nama_kandidat" name="nama_kandidat"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label for="tgl_penugasan" class="form-label">Tanggal Penugasan</label>
                                    <input type="date" class="form-control" id="tgl_penugasan" name="tgl_penugasan"
                                        required min="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="createSubmitBtn">
                                <span id="createSubmitText">Simpan</span>
                                <span id="createSubmitSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="editModalLabel">Edit Surat Tugas</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="edit_no_surat" class="form-label">Nomor Surat</label>
                                    <input type="text" class="form-control" id="edit_no_surat" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_nama_kandidat" class="form-label">Nama Kandidat</label>
                                    <input type="text" class="form-control" id="edit_nama_kandidat"
                                        name="nama_kandidat" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="edit_tgl_penugasan" class="form-label">Tanggal Penugasan</label>
                                    <input type="date" class="form-control" id="edit_tgl_penugasan"
                                        name="tgl_penugasan" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="editSubmitBtn">
                                <span id="editSubmitText">Simpan</span>
                                <span id="editSubmitSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dataTable = new simpleDatatables.DataTable("#suratTugasTable", {
                searchable: true,
                fixedHeight: true,
                perPage: 10,
                labels: {
                    placeholder: "Cari...",
                    perPage: "Data per halaman",
                    noRows: "Data tidak ditemukan",
                    info: "Menampilkan {start} sampai {end} dari {rows} data",
                }
            });

            // Handle create form submission
            document.getElementById('createForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                const submitBtn = document.getElementById('createSubmitBtn');
                const submitText = document.getElementById('createSubmitText');
                const submitSpinner = document.getElementById('createSubmitSpinner');

                submitBtn.disabled = true;
                submitText.textContent = 'Menyimpan...';
                submitSpinner.classList.remove('d-none');

                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw err;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'Gagal menyimpan data');
                    }

                    Swal.fire({
                        title: 'Sukses!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .catch(error => {
                    let errorMsg = error.message || 'Terjadi kesalahan saat menyimpan data';
                    if (error.errors) {
                        errorMsg = Object.values(error.errors).join('<br>');
                    }
                    Swal.fire({
                        title: 'Error!',
                        html: errorMsg,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitText.textContent = 'Simpan';
                    submitSpinner.classList.add('d-none');
                });
            });

            // Edit button handler
            document.addEventListener('click', function(e) {
                if (e.target.closest('.edit-btn')) {
                    const btn = e.target.closest('.edit-btn');
                    const id = btn.dataset.id;
                    const url = "{{ route('surat-tugas.edit', ':id') }}".replace(':id', id);
                    const updateUrl = "{{ route('surat-tugas.update', ':id') }}".replace(':id', id);

                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    btn.disabled = true;

                    fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (!data.success) {
                                throw new Error(data.message || 'Failed to load data');
                            }

                            // Fill form
                            document.getElementById('edit_no_surat').value = data.data.no_surat;
                            document.getElementById('edit_nama_kandidat').value = data.data
                                .nama_kandidat;
                            document.getElementById('edit_tgl_penugasan').value = data.data
                                .tgl_penugasan.split(' ')[0];
                            document.getElementById('editForm').action = updateUrl;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: error.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        })
                        .finally(() => {
                            btn.innerHTML = '<i class="fas fa-edit"></i>';
                            btn.disabled = false;
                        });
                }
            });

            // Form submission
            document.getElementById('editForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                const submitBtn = document.getElementById('editSubmitBtn');
                const submitText = document.getElementById('editSubmitText');
                const submitSpinner = document.getElementById('editSubmitSpinner');

                submitBtn.disabled = true;
                submitText.textContent = 'Memproses...';
                submitSpinner.classList.remove('d-none');

                fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => {
                                throw err;
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message || 'Update failed');
                        }

                        Swal.fire({
                            title: 'Sukses!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = data.redirect || window.location.href;
                        });
                    })
                    .catch(error => {
                        let errorMsg = error.message || 'Terjadi kesalahan';
                        if (error.errors) {
                            errorMsg = Object.values(error.errors).join('<br>');
                        }
                        Swal.fire({
                            title: 'Error!',
                            html: errorMsg,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitText.textContent = 'Simpan Perubahan';
                        submitSpinner.classList.add('d-none');
                    });
            });

            // Delete button handler
            document.addEventListener('click', function(e) {
                if (e.target.closest('.delete-btn')) {
                    const btn = e.target.closest('.delete-btn');
                    const id = btn.dataset.id;
                    const url = "{{ route('surat-tugas.destroy', ':id') }}".replace(':id', id);

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                            btn.disabled = true;

                            fetch(url, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => {
                                if (!response.ok) {
                                    return response.json().then(err => {
                                        throw err;
                                    });
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (!data.success) {
                                    throw new Error(data.message || 'Gagal menghapus data');
                                }

                                Swal.fire({
                                    title: 'Terhapus!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.reload();
                                });
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Error!',
                                    text: error.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            })
                            .finally(() => {
                                btn.innerHTML = '<i class="fas fa-trash"></i>';
                                btn.disabled = false;
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
