@extends('admin.layout.app')
@section('title', 'Surat Tugas Promotor')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 text-gray-800">Data Surat Tugas Promotor</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item">Kumpulan data surat tugas promotor</li>
        </ol>

        <div class="card border-0 mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal">
                    <i class="fas fa-plus"></i> Tambah Surat
                </button>
            </div>

            <div class="card-body border">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        @if (session('action') == 'generate_surat')
                            )
                            <div id="generatingSpinner" class="spinner-border spinner-border-sm ml-2" role="status">
                                <span class="sr-only">Generating...</span>
                            </div>
                        @endif
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session('delete_success'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('delete_success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="suratTugasTable" width="100%" cellspacing="0">
                        <thead class="bg-light">
                            <tr>
                                <th>No.</th>
                                <th>Nama Promotor</th>
                                <th>Penempatan</th>
                                <th>Tanggal Penugasan</th>
                                <th>Tanggal Pembuatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suratTugasPromotor as $index => $surat)
                                <tr data-id="{{ $surat->id_surat_tugas_promotor }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $surat->nama_kandidat }}</td>
                                    <td>
                                        @if (is_array($surat->penempatan))
                                            {{ implode(', ', $surat->penempatan) }}
                                        @else
                                            {{ $surat->penempatan }}
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($surat->tgl_penugasan)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($surat->tgl_surat_pembuatan)->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            @if ($surat->file_path_pdf)
                                                <a href="{{ route('surat-tugas-promotor.generate-pdf', $surat->id_surat_tugas_promotor) }}"
                                                    class="btn btn-sm btn-danger" target="_blank">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            @endif

                                            @if ($surat->file_path_docx)
                                                <a href="{{ route('surat-tugas-promotor.generate-word', $surat->id_surat_tugas_promotor) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-file-word"></i>
                                                </a>
                                            @endif

                                            <button class="btn btn-sm btn-info edit-btn" data-toggle="modal"
                                                data-target="#editModal" data-id="{{ $surat->id_surat_tugas_promotor }}">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button class="btn btn-sm btn-danger delete-btn" data-toggle="modal"
                                                data-target="#deleteModal" data-id="{{ $surat->id_surat_tugas_promotor }}">
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
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createModalLabel">Buat Surat Tugas Promotor Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createForm" action="{{ route('surat-tugas-promotor.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nama_kandidat" class="form-label">Nama Promotor</label>
                                <input type="text" class="form-control" id="nama_kandidat" name="nama_kandidat" required>
                            </div>
                            <div class="col-md-6">
                                <label for="penempatan" class="form-label">Penempatan</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="penempatan" name="penempatan"
                                        placeholder="Masukkan lokasi dan tekan Enter" data-role="tagsinput">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    Tambahkan koma setelah setiap lokasi
                                </small>
                            </div>
                            <div class="col-md-6">
                                <label for="tgl_penugasan" class="form-label">Tanggal Penugasan</label>
                                <input type="date" class="form-control" id="tgl_penugasan" name="tgl_penugasan"
                                    required min="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="createSubmitBtn">
                            <span id="createSubmitText">Simpan</span>
                            <span id="createSubmitSpinner" class="spinner-border spinner-border-sm d-none" role="status"
                                aria-hidden="true"></span>
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
                    <h5 class="modal-title" id="editModalLabel">Edit Surat Tugas Promotor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_nama_kandidat" class="form-label">Nama Promotor</label>
                                <input type="text" class="form-control" id="edit_nama_kandidat" name="nama_kandidat"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_penempatan" class="form-label">Penempatan</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="edit_penempatan" name="penempatan"
                                        placeholder="Masukkan lokasi dan tekan Enter" data-role="tagsinput">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    Tekan Enter atau koma setelah setiap lokasi
                                </small>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_tgl_penugasan" class="form-label">Tanggal Penugasan</label>
                                <input type="date" class="form-control" id="edit_tgl_penugasan" name="tgl_penugasan"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="editSubmitBtn">
                            <span id="editSubmitText">Simpan Perubahan</span>
                            <span id="editSubmitSpinner" class="spinner-border spinner-border-sm d-none" role="status"
                                aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Hapus Surat Tugas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deleteForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        Apakah anda yakin akan menghapus Surat Tugas untuk Promotor <strong
                            id="deletePromotorName"></strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <span id="deleteSubmitText">Hapus</span>
                            <span id="deleteSubmitSpinner" class="spinner-border spinner-border-sm d-none" role="status"
                                aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @if (session('action') == 'generate_surat')
        <script>
            $(document).ready(function() {
                let idGenerate = '{{ session('id_generate') }}';
                let urlGenerate = '{{ route('surat-tugas-promotor.generate-file') }}';

                $.post(urlGenerate, {
                    '_token': '{{ csrf_token() }}',
                    'id': idGenerate
                }, function(response) {
                    if (response.status === 'success') {
                        // Refresh the page after successful generation
                        window.location.reload();
                    } else {
                        alert('Gagal membuat dokumen: ' + (response.message || 'Terjadi kesalahan'));
                    }
                }).fail(function() {
                    alert('Gagal terhubung ke server');
                });
            });
        </script>
    @endif

    <script>
        $(document).ready(function() {
            let table = new DataTable('#suratTugasTable');

            function initTagsInput(selector) {
                $(selector).tagsinput({
                    trimValue: true,
                    confirmKeys: [13, 44],
                    maxTags: 10,
                    maxChars: 50,
                    cancelConfirmKeysOnEmpty: false,
                    tagClass: 'badge badge-info mr-1 mb-1',
                    focusClass: 'is-focused',
                    onTagExists: function(item, $tag) {
                        $tag.hide().fadeIn();
                        alert('Lokasi ini sudah ditambahkan: ' + item);
                    }
                });

                $(selector).next('.bootstrap-tagsinput').addClass('form-control');
            }

            initTagsInput('#penempatan');
            initTagsInput('#edit_penempatan');

            $('#penempatan').on('focus', function() {
                $('.tags-preview .badge').removeClass('d-none');
            }).on('blur', function() {
                $('.tags-preview .badge').addClass('d-none');
            });

            // Edit button click handler
            $(document).on('click', '.edit-btn', function() {
                let id = $(this).data('id');
                let url = '{{ route('surat-tugas-promotor.edit', ['id' => ':id']) }}'.replace(':id', id);
                let $editModal = $('#editModal');
                let $editForm = $('#editForm');
                let $editPenempatan = $('#edit_penempatan');

                // Show loading state
                $editForm.find('.modal-body').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-2">Memuat data...</p>
            </div>
        `);

                $.get(url)
                    .done(function(response) {
                        if (response.success) {
                            let data = response.data;
                            let editUrl = '{{ route('surat-tugas-promotor.update', ['id' => ':id']) }}'
                                .replace(':id', id);

                            $editForm.attr('action', editUrl);
                            $editForm.find('#edit_nama_kandidat').val(data.nama_kandidat);
                            $editForm.find('#edit_tgl_penugasan').val(data.tgl_penugasan.substring(0,
                                10));

                            // Clear and add new tags for penempatan
                            $editPenempatan.tagsinput('removeAll');

                            if (data.penempatan) {
                                let locations = Array.isArray(data.penempatan) ?
                                    data.penempatan :
                                    data.penempatan.split(',').filter(Boolean);

                                locations.forEach(function(lokasi) {
                                    if (lokasi.trim()) {
                                        $editPenempatan.tagsinput('add', lokasi.trim());
                                    }
                                });
                            }

                            // Restore original form content
                            $editForm.find('.modal-body').html(`
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_nama_kandidat" class="form-label">Nama Promotor</label>
                                <input type="text" class="form-control" id="edit_nama_kandidat" name="nama_kandidat" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_penempatan" class="form-label">Penempatan</label>
                                <div class="input-group">
                                    <input type="text"
                                           class="form-control"
                                           id="edit_penempatan"
                                           name="penempatan"
                                           placeholder="Masukkan lokasi dan tekan Enter"
                                           data-role="tagsinput">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    Tekan Enter atau koma setelah setiap lokasi
                                </small>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_tgl_penugasan" class="form-label">Tanggal Penugasan</label>
                                <input type="date"
                                       class="form-control"
                                       id="edit_tgl_penugasan"
                                       name="tgl_penugasan"
                                       required>
                            </div>
                        </div>
                    `);

                            // Reinitialize tags input for the edit modal
                            initTagsInput('#edit_penempatan');
                        }
                    })
                    .fail(function() {
                        $editForm.find('.modal-body').html(`
                    <div class="alert alert-danger">
                        Gagal memuat data. Silakan coba lagi.
                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                `);
                    });
            });

            // Delete button click handler
            $(document).on('click', '.delete-btn', function() {
                let id = $(this).data('id');
                let url = '{{ route('surat-tugas-promotor.edit', ['id' => ':id']) }}'.replace(':id', id);
                let deleteUrl = '{{ route('surat-tugas-promotor.destroy', ['id' => ':id']) }}'.replace(
                    ':id', id);
                let $deleteModal = $('#deleteModal');

                // Show loading state
                $deleteModal.find('.modal-body').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-danger" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        `);

                $.get(url)
                    .done(function(response) {
                        if (response.success) {
                            $('#deleteForm').attr('action', deleteUrl);
                            $deleteModal.find('.modal-body').html(`
                        Apakah anda yakin akan menghapus Surat Tugas untuk Promotor <strong id="deletePromotorName">${response.data.nama_kandidat}</strong>?
                        <div class="mt-3">
                            <strong>Detail:</strong>
                            <ul class="mt-2">
                                <li>Penempatan: ${Array.isArray(response.data.penempatan) ? response.data.penempatan.join(', ') : response.data.penempatan}</li>
                                <li>Tanggal Penugasan: ${new Date(response.data.tgl_penugasan).toLocaleDateString('id-ID')}</li>
                            </ul>
                        </div>
                    `);
                        }
                    })
                    .fail(function() {
                        $deleteModal.find('.modal-body').html(`
                    <div class="alert alert-danger">
                        Gagal memuat data. Silakan coba lagi.
                    </div>
                `);
                    });
            });

            // Form submission handlers
            $('#createForm, #editForm, #deleteForm').submit(function(e) {
                let $form = $(this);
                let $submitBtn = $form.find('[type="submit"]');
                let $submitText = $submitBtn.find('#createSubmitText, #editSubmitText, #deleteSubmitText');
                let $submitSpinner = $submitBtn.find('.spinner-border');

                // Prevent double submission
                if ($form.data('submitted') === true) {
                    e.preventDefault();
                    return;
                }

                $form.data('submitted', true);
                $submitText.addClass('d-none');
                $submitSpinner.removeClass('d-none');
                $submitBtn.prop('disabled', true);

                // Re-enable if there's an error
                setTimeout(function() {
                    if ($form.data('submitted') === true) {
                        $form.data('submitted', false);
                        $submitText.removeClass('d-none');
                        $submitSpinner.addClass('d-none');
                        $submitBtn.prop('disabled', false);
                    }
                }, 5000);
            });

            // Handle modal show/hide events
            $('#createModal, #editModal, #deleteModal').on('hidden.bs.modal', function() {
                // Reset forms when modal is closed
                $(this).find('form')[0].reset();
                $(this).find('.is-invalid').removeClass('is-invalid');
                $(this).find('.invalid-feedback').remove();

                // Reset submission state
                $(this).find('form').data('submitted', false);
                $(this).find('[type="submit"]').prop('disabled', false)
                    .find('.spinner-border').addClass('d-none')
                    .siblings().removeClass('d-none');
            });

            // Handle document generation
            $('a[href*="generate-pdf"], a[href*="generate-word"]').click(function(e) {
                let $link = $(this);
                let $icon = $link.find('i');
                let originalClass = $icon.attr('class');

                // Show loading state
                $icon.attr('class', 'fas fa-spinner fa-spin');
                $link.addClass('disabled');

                // Revert after 5 seconds if still processing
                setTimeout(function() {
                    $icon.attr('class', originalClass);
                    $link.removeClass('disabled');
                }, 5000);
            });

            // Auto-focus first input when modal opens
            $('#createModal').on('shown.bs.modal', function() {
                $(this).find('#nama_kandidat').focus();
            });

            $('#editModal').on('shown.bs.modal', function() {
                $(this).find('#edit_nama_kandidat').focus();
            });

            // Initialize date pickers with restrictions
            $('#tgl_penugasan').attr('min', new Date().toISOString().split('T')[0]);
            $('#edit_tgl_penugasan').on('focus', function() {
                this.min = new Date().toISOString().split('T')[0];
            });
        });
    </script>

    <!-- Include necessary CSS/JS for tags input -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
@endsection
