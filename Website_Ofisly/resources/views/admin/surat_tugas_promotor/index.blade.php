@extends('admin.layout.app')
@section('title', 'Surat Tugas Promotor Indosat')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 text-gray-800">Data Surat Tugas Promotor Indosat</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item">Kumpulan data surat tugas untuk promotor indosat</li>
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

                <div id="alert-field"></div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="suratTugasPromotorTable" width="100%"
                        cellspacing="0">
                        <thead class="bg-light">
                            <tr>
                                <th>No.</th>
                                <th>Nama Kandidat</th>
                                <th>Penempatan</th>
                                <th>Tanggal Penugasan</th>
                                <th>Tanggal Pembuatan</th>
                                <th>Status File</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($suratTugasPromotor as $surat)
                                <tr data-id="{{ $surat->id_surat_tugas_promotor }}">
                                    <td>{{ $i }}</td>
                                    <td class="nama-kandidat">{{ $surat->nama_kandidat }}</td>
                                    <td>
                                        @if (is_array($surat->penempatan))
                                            @foreach ($surat->penempatan as $lokasi)
                                                <span class="badge badge-primary mr-1">{{ $lokasi }}</span>
                                            @endforeach
                                        @else
                                            <span class="badge badge-primary">{{ $surat->penempatan }}</span>
                                        @endif
                                    </td>
                                    <td class="tgl-penugasan">
                                        {{ \Carbon\Carbon::parse($surat->tgl_penugasan)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($surat->tgl_surat_pembuatan)->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <div id="status-container-{{ $surat->id_surat_tugas_promotor }}">
                                            @if ($surat->file_path_pdf && $surat->file_path_docx)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check"></i> Tersedia
                                                </span>
                                            @else
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-spinner fa-spin"></i> Proses Generate
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-danger" id="btn_pdf_{{ $surat->id_surat_tugas_promotor }}"
                                                onclick="getInfoFile(this, '{{ $surat->id_surat_tugas_promotor }}', 'pdf')"
                                                {{ !$surat->file_path_pdf ? 'disabled' : '' }}>
                                                <i class="fas fa-file-pdf"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" id="btn_word_{{ $surat->id_surat_tugas_promotor }}"
                                                onclick="getInfoFile(this, '{{ $surat->id_surat_tugas_promotor }}', 'docx')"
                                                {{ !$surat->file_path_docx ? 'disabled' : '' }}>
                                                <i class="fas fa-file-word"></i>
                                            </button>
                                            <button class="btn btn-sm btn-info edit-btn" data-toggle="modal"
                                                data-target="#editModal" onclick="getDataEdit(this)"
                                                data-id="{{ $surat->id_surat_tugas_promotor }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-btn" data-toggle="modal"
                                                data-target="#deleteModal" onclick="getDataHapus(this)"
                                                data-id="{{ $surat->id_surat_tugas_promotor }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @php
                                    $i++;
                                @endphp
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
                        <h5 class="modal-title" id="createModalLabel">Buat Surat Tugas Promotor Baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="createForm" action="{{ route('surat-tugas-promotor.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <label for="nama_kandidat" class="form-label">Nama Kandidat</label>
                                    <input type="text" class="form-control" id="nama_kandidat" name="nama_kandidat"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tgl_penugasan" class="form-label">Tanggal Penugasan</label>
                                    <input type="date" placeholder="Pilih Tanggal" class="form-control"
                                        id="tgl_penugasan" name="tgl_penugasan" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="penempatan" class="form-label">Penempatan</label>
                                    <div class="penempatan-container mb-2">
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control penempatan-input"
                                                placeholder="Masukkan lokasi penempatan">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-primary" type="button"
                                                    id="tambahPenempatan">Tambah</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="daftarPenempatan" class="d-flex flex-wrap gap-2 mb-2"></div>
                                    <input type="hidden" id="penempatan" name="penempatan" required>
                                    <small class="form-text text-muted">Klik tombol "Tambah" untuk menambahkan lokasi
                                        penempatan.</small>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="createSubmitBtn">
                                <span id="createSubmitText">Simpan</span>
                                <span id="createSubmitSpinner" class="spinner-border spinner-border-sm d-none"
                                    role="status" aria-hidden="true"></span>
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
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <label for="edit_nama_kandidat" class="form-label">Nama Kandidat</label>
                                    <input type="text" class="form-control" id="edit_nama_kandidat"
                                        name="edit_nama_kandidat" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_tgl_penugasan" class="form-label">Tanggal Penugasan</label>
                                    <input type="date" placeholder="Pilih Tanggal" class="form-control"
                                        id="edit_tgl_penugasan" name="edit_tgl_penugasan" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_tgl_surat_pembuatan" class="form-label">Tanggal Pembuatan
                                        Surat</label>
                                    <input type="date" class="form-control" id="edit_tgl_surat_pembuatan"
                                        name="edit_tgl_surat_pembuatan" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="edit_penempatan" class="form-label">Penempatan</label>
                                    <div class="penempatan-container mb-2">
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control edit-penempatan-input"
                                                placeholder="Masukkan lokasi penempatan">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-primary" type="button"
                                                    id="editTambahPenempatan">Tambah</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="editDaftarPenempatan" class="d-flex flex-wrap gap-2 mb-2"></div>
                                    <input type="hidden" id="edit_penempatan" name="edit_penempatan" required>
                                    <small class="form-text text-muted">Klik tombol "Tambah" untuk menambahkan lokasi
                                        penempatan.</small>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="editSubmitBtn">
                                <span id="editSubmitText">Simpan Perubahan</span>
                                <span id="editSubmitSpinner" class="spinner-border spinner-border-sm d-none"
                                    role="status" aria-hidden="true"></span>
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
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            Apakah Anda yakin ingin menghapus data Surat Tugas untuk Promotor <strong
                                id="hapus_nama_kandidat"></strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                id="deleteCancelBtn">Batal</button>
                            <button type="submit" class="btn btn-danger" id="deleteSubmitBtn" disabled>
                                <span id="deleteSubmitText">Hapus</span>
                                <span id="deleteSubmitSpinner" class="spinner-border spinner-border-sm d-none"
                                    role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Socket.IO Client -->
    <script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>

    <script>
        // Global Socket.IO connection
        let socket = null;
        let connectedSuratIds = new Set();

        $(document).ready(function() {
            let table = new DataTable('#suratTugasPromotorTable');

            // Initialize Socket.IO connection
            initializeSocketConnection();

            // Inisialisasi flatpickr untuk modal create
            flatpickr("#tgl_penugasan", {
                minDate: 'today',
                dateFormat: "Y-m-d",
            });

            // Reset tombol hapus saat modal ditutup
            $('#deleteModal').on('hidden.bs.modal', function() {
                $('#deleteSubmitBtn').prop('disabled', true);
                $('#hapus_nama_kandidat').text('');
            });

            // Fungsi untuk menambahkan lokasi penempatan (create modal)
            $('#tambahPenempatan').click(function() {
                const input = $('.penempatan-input');
                const value = input.val().trim();

                if (value) {
                    // Tambahkan badge untuk lokasi baru
                    const badge = `<span class="badge badge-primary p-2 d-flex align-items-center mb-2">
                        ${value}
                        <button type="button" class="btn btn-sm btn-link text-danger p-0 ml-2 hapus-lokasi">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>`;

                    $('#daftarPenempatan').append(badge);
                    input.val('');

                    // Perbarui input hidden
                    updatePenempatanInput();
                }
            });

            // Fungsi untuk menambahkan lokasi penempatan (edit modal)
            $('#editTambahPenempatan').click(function() {
                const input = $('.edit-penempatan-input');
                const value = input.val().trim();

                if (value) {
                    // Tambahkan badge untuk lokasi baru
                    const badge = `<span class="badge badge-primary p-2 d-flex align-items-center mb-2">
                        ${value}
                        <button type="button" class="btn btn-sm btn-link text-danger p-0 ml-2 hapus-lokasi">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>`;

                    $('#editDaftarPenempatan').append(badge);
                    input.val('');

                    // Perbarui input hidden
                    updateEditPenempatanInput();
                }
            });

            // Fungsi untuk menghapus lokasi penempatan (delegasi event)
            $(document).on('click', '.hapus-lokasi', function() {
                $(this).closest('.badge').remove();

                // Perbarui input hidden berdasarkan modal yang aktif
                if ($('#createModal').hasClass('show')) {
                    updatePenempatanInput();
                } else if ($('#editModal').hasClass('show')) {
                    updateEditPenempatanInput();
                }
            });

            // Fungsi untuk memperbarui input hidden penempatan (create modal)
            function updatePenempatanInput() {
                const locations = [];
                $('#daftarPenempatan .badge').each(function() {
                    // Ambil teks lokasi (exclude tombol hapus)
                    const locationText = $(this).contents().filter(function() {
                        return this.nodeType === 3; // NodeType 3 adalah text node
                    }).text().trim();

                    if (locationText) {
                        locations.push(locationText);
                    }
                });

                $('#penempatan').val(locations.join(', '));
            }

            // Fungsi untuk memperbarui input hidden penempatan (edit modal)
            function updateEditPenempatanInput() {
                const locations = [];
                $('#editDaftarPenempatan .badge').each(function() {
                    // Ambil teks lokasi (exclude tombol hapus)
                    const locationText = $(this).contents().filter(function() {
                        return this.nodeType === 3; // NodeType 3 adalah text node
                    }).text().trim();

                    if (locationText) {
                        locations.push(locationText);
                    }
                });

                $('#edit_penempatan').val(JSON.stringify(locations));
            }

            // Reset modal create ketika ditutup
            $('#createModal').on('hidden.bs.modal', function() {
                $('#daftarPenempatan').empty();
                $('#penempatan').val('');
                $('.penempatan-input').val('');
            });

            // Reset modal edit ketika ditutup
            $('#editModal').on('hidden.bs.modal', function() {
                $('#editDaftarPenempatan').empty();
                $('#edit_penempatan').val('');
                $('.edit-penempatan-input').val('');
            });

            // Handle submit form edit
            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                const submitBtn = $('#editSubmitBtn');
                const submitText = $('#editSubmitText');
                const submitSpinner = $('#editSubmitSpinner');

                // Show loading state
                submitBtn.prop('disabled', true);
                submitText.addClass('d-none');
                submitSpinner.removeClass('d-none');

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert('Gagal memperbarui data: ' + (response.message || 'Terjadi kesalahan'));
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            // Validation errors
                            const errors = xhr.responseJSON.errors;
                            let errorMessage = 'Validasi gagal:\n';
                            for (const field in errors) {
                                errorMessage += `- ${errors[field][0]}\n`;
                            }
                            alert(errorMessage);
                        } else {
                            alert('Terjadi kesalahan sistem. Silakan coba lagi.');
                        }
                    },
                    complete: function() {
                        // Reset loading state
                        submitBtn.prop('disabled', false);
                        submitText.removeClass('d-none');
                        submitSpinner.addClass('d-none');
                    }
                });
            });
        });

        // Initialize Socket.IO Connection
        function initializeSocketConnection() {
            const flaskUrl = '{{ env("FLASK_API_URL", "http://localhost:5000") }}';

            try {
                socket = io(flaskUrl, {
                    transports: ['websocket', 'polling'],
                    timeout: 10000
                });

                socket.on('connect', function() {
                    console.log('Connected to Flask WebSocket');
                    // Subscribe to existing processing items
                    subscribeToExistingProcessing();
                });

                socket.on('connect', function() {
                    console.log('WebSocket connection established');
                    showAlert('Terhubung ke server realtime', 'success');
                });

                // PERBAIKAN: Gunakan event yang sesuai dari Flask
                socket.on('generation_status_promotor', function(data) {
                    console.log('Received status update:', data);
                    handleStatusUpdate(data);
                });

                socket.on('subscribed', function(data) {
                    console.log('Subscribed to surat_id:', data.surat_id);
                    connectedSuratIds.add(data.surat_id);
                });

                socket.on('disconnect', function() {
                    console.log('Disconnected from Flask WebSocket');
                    showAlert('Koneksi terputus, mencoba menghubungkan kembali...', 'warning');
                });

                socket.on('connect_error', function(error) {
                    console.error('WebSocket connection error:', error);
                    // Fallback to polling if WebSocket fails
                    setTimeout(() => {
                        if (!socket.connected) {
                            socket.io.opts.transports = ['polling'];
                            socket.connect();
                        }
                    }, 1000);
                });

            } catch (error) {
                console.error('Failed to initialize WebSocket:', error);
                // Fallback to AJAX polling
                startPollingFallback();
            }
        }

        // Subscribe to WebSocket updates for a specific surat_id
        function subscribeToSuratUpdates(suratId) {
            if (socket && socket.connected) {
                socket.emit('subscribe_to_progress', { surat_id: suratId });
                connectedSuratIds.add(suratId);
                console.log('Subscribed to surat updates:', suratId);
            } else {
                console.warn('WebSocket not connected, cannot subscribe to:', suratId);
                // Fallback to AJAX polling for this specific ID
                startPollingForSurat(suratId);
            }
        }

        // Handle status updates from WebSocket
        function handleStatusUpdate(data) {
            const suratId = data.surat_id;
            const status = data.status;
            const message = data.message || '';
            const progress = data.progress || 0;

            console.log(`Status update for ${suratId}: ${status} - ${message} (${progress}%)`);

            // Update status badge
            updateStatusBadge(suratId, status, message, progress);

            // Handle completed status
            if (status === 'completed') {
                // Enable download buttons
                enableDownloadButtons(suratId);
                // Show success message
                showAlert(`File untuk surat ID ${suratId} berhasil digenerate!`, 'success');
            }

            // Handle error status
            if (status === 'error') {
                showAlert(`Error pada surat ID ${suratId}: ${message}`, 'danger');
            }
        }

        // Update status badge based on status
        function updateStatusBadge(suratId, status, message, progress = 0) {
            const statusContainer = $(`#status-container-${suratId}`);

            let badgeClass = 'badge-secondary';
            let badgeText = 'Unknown';
            let spinner = '';
            let progressBar = '';

            switch(status) {
                case 'processing':
                    badgeClass = 'badge-warning';
                    badgeText = message || 'Proses Generate';
                    spinner = '<i class="fas fa-spinner fa-spin mr-1"></i>';
                    if (progress > 0) {
                        progressBar = `<small class="ml-1">(${progress}%)</small>`;
                    }
                    break;
                case 'completed':
                    badgeClass = 'badge-success';
                    badgeText = 'Tersedia';
                    spinner = '<i class="fas fa-check mr-1"></i>';
                    break;
                case 'error':
                    badgeClass = 'badge-danger';
                    badgeText = message || 'Error';
                    spinner = '<i class="fas fa-exclamation-triangle mr-1"></i>';
                    break;
                case 'regenerating':
                    badgeClass = 'badge-info';
                    badgeText = 'Regenerate';
                    spinner = '<i class="fas fa-sync-alt fa-spin mr-1"></i>';
                    break;
                default:
                    badgeClass = 'badge-secondary';
                    badgeText = status;
            }

            statusContainer.html(`
                <span class="badge ${badgeClass}">
                    ${spinner}${badgeText}${progressBar}
                </span>
            `);
        }

        // Enable download buttons when files are ready
        function enableDownloadButtons(suratId) {
            $(`#btn_pdf_${suratId}`).prop('disabled', false);
            $(`#btn_word_${suratId}`).prop('disabled', false);

            // Update badge to show available
            updateStatusBadge(suratId, 'completed', 'Tersedia');
        }

        // Show alert message
        function showAlert(message, type = 'info') {
            const alertField = $('#alert-field');
            alertField.html(`
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `);

            // Auto dismiss after 5 seconds for success/info, 10 seconds for warnings/errors
            const dismissTime = type === 'success' || type === 'info' ? 5000 : 10000;
            setTimeout(() => {
                alertField.empty();
            }, dismissTime);
        }

        // Fallback: AJAX polling for WebSocket-disconnected scenarios
        function startPollingFallback() {
            console.log('Starting AJAX polling fallback...');
            // Poll every 5 seconds for all processing items
            setInterval(() => {
                $('tr[data-id]').each(function() {
                    const suratId = $(this).data('id');
                    const currentStatus = $(`#status-container-${suratId} .badge`).text().trim();

                    if (currentStatus.includes('Proses') || currentStatus.includes('Generate') ||
                        currentStatus.includes('processing') || !currentStatus.includes('Tersedia')) {
                        checkFileStatus(suratId);
                    }
                });
            }, 5000);
        }

        // Poll for specific surat ID
        function startPollingForSurat(suratId) {
            console.log(`Starting polling for surat_id: ${suratId}`);
            const pollInterval = setInterval(() => {
                checkFileStatus(suratId).done(function(data) {
                    if (data.status === 'completed' || data.status === 'error') {
                        clearInterval(pollInterval);
                    }
                });
            }, 3000);
        }

        // Check file status via AJAX
        function checkFileStatus(suratId) {
            return $.get(`/api/surat-promotor/status/${suratId}`)
                .done(function(response) {
                    if (response.success) {
                        const status = response.status;
                        if (status.pdf && status.docx) {
                            updateStatusBadge(suratId, 'completed', 'Tersedia');
                            enableDownloadButtons(suratId);
                        } else {
                            updateStatusBadge(suratId, 'processing', 'Proses Generate');
                        }
                    }
                })
                .fail(function() {
                    console.error('Failed to check file status for:', suratId);
                });
        }

        // Subscribe to existing processing items on page load
        function subscribeToExistingProcessing() {
            $('tr[data-id]').each(function() {
                const suratId = $(this).data('id');
                const currentStatus = $(`#status-container-${suratId} .badge`).text().trim();

                // Subscribe jika status masih proses atau file belum tersedia
                if (currentStatus.includes('Proses') || currentStatus.includes('Generate') ||
                    !currentStatus.includes('Tersedia')) {
                    subscribeToSuratUpdates(suratId);
                }
            });
        }

        // Fungsi untuk generate file
        function generateFile(id) {
            let urlGenerate = '{{ route('surat-tugas-promotor.generate-file') }}';
            $.post(urlGenerate, {
                '_token': '{{ csrf_token() }}',
                'id': id
            }, function(data, status) {
                console.log('Generate file response:', data);
                if (data.status === 'success' || data.success) {
                    // Subscribe to WebSocket updates
                    subscribeToSuratUpdates(id);
                    // Update status badge
                    updateStatusBadge(id, 'processing', 'Proses Generate');
                    showAlert('Proses generate file dimulai...', 'info');
                } else {
                    alert('Gagal memulai proses generate file.');
                }
            }).fail(function() {
                console.error('Failed to trigger file generation.');
                alert('Terjadi kesalahan saat generate file.');
            });
        }

        function getDataEdit(element) {
            let idEdit = $(element).data('id');
            let urlEdit = '{{ route('surat-tugas-promotor.edit', ['id' => '__ID__']) }}'.replace('__ID__', idEdit);
            let urlUpdate = '{{ route('surat-tugas-promotor.update', ['id' => '__ID__']) }}'.replace('__ID__', idEdit);

            // Reset modal edit sebelum memuat data baru
            $('#editDaftarPenempatan').empty();
            $('.edit-penempatan-input').val('');

            $.get(urlEdit, function(response) {
                if (response.success) {
                    let data = response.data;
                    $('#editForm').attr('action', urlUpdate);

                    $('#edit_nama_kandidat').val(data.nama_kandidat);
                    $('#edit_tgl_penugasan').val(data.tgl_penugasan.substring(0, 10));
                    $('#edit_tgl_surat_pembuatan').val(data.tgl_surat_pembuatan.substring(0, 10));

                    // Proses data penempatan
                    let penempatanArray = [];

                    if (Array.isArray(data.penempatan)) {
                        penempatanArray = data.penempatan;
                    } else if (typeof data.penempatan === 'string') {
                        // Coba parse jika berupa JSON string
                        try {
                            const parsed = JSON.parse(data.penempatan);
                            penempatanArray = Array.isArray(parsed) ? parsed : [data.penempatan];
                        } catch (e) {
                            // Jika bukan JSON, split by comma
                            penempatanArray = data.penempatan.split(',').map(item => item.trim());
                        }
                    } else {
                        penempatanArray = [data.penempatan];
                    }

                    // Filter empty values
                    penempatanArray = penempatanArray.filter(item => item && item.trim() !== '');

                    // Tambahkan setiap lokasi sebagai badge
                    penempatanArray.forEach(function(lokasi) {
                        if (lokasi && lokasi.trim()) {
                            const badge = `<span class="badge badge-primary p-2 d-flex align-items-center mb-2">
                                ${lokasi.trim()}
                                <button type="button" class="btn btn-sm btn-link text-danger p-0 ml-2 hapus-lokasi">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>`;
                            $('#editDaftarPenempatan').append(badge);
                        }
                    });

                    // Perbarui input hidden
                    $('#edit_penempatan').val(JSON.stringify(penempatanArray));

                    // Inisialisasi flatpickr untuk modal edit
                    flatpickr("#edit_tgl_penugasan", {
                        dateFormat: "Y-m-d",
                        defaultDate: data.tgl_penugasan
                    });

                    flatpickr("#edit_tgl_surat_pembuatan", {
                        dateFormat: "Y-m-d",
                        defaultDate: data.tgl_surat_pembuatan
                    });

                } else {
                    alert('Gagal memuat data. Silakan coba lagi.');
                }
            }).fail(function(xhr, status, error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data.');
            });
        }

        function getDataHapus(element) {
            let idDelete = $(element).data('id');
            let namaKandidat = $(element).closest('tr').find('.nama-kandidat').text();
            let urlDelete = '{{ route('surat-tugas-promotor.destroy', ['id' => '__ID__']) }}'.replace('__ID__', idDelete);

            $('#deleteForm').attr('action', urlDelete);
            $('#hapus_nama_kandidat').text(namaKandidat);
            $('#deleteSubmitBtn').prop('disabled', false);
        }

        function getInfoFile(element, id, type) {
            let alertField = document.getElementById('alert-field');
            element.innerHTML = `<i class="fas fa-spin fa-sync-alt"></i>`;
            let btnPDF = document.getElementById('btn_pdf_' + id);
            let btnDocx = document.getElementById('btn_word_' + id);

            btnPDF.setAttribute('disabled', '');
            btnDocx.setAttribute('disabled', '');

            let urlFileCheck = '{{ route('surat-tugas-promotor.file-check', ['id' => '__ID__', 'type' => '__TYPE__']) }}';
            $.get(urlFileCheck.replace('__ID__', id).replace('__TYPE__', type), function(data, status) {
                if(data.status) {
                    if(type == 'pdf') {
                        let urlGenerate = '{{ route('surat-tugas-promotor.generate-pdf', ['id' => '__ID__']) }}';
                        window.open(urlGenerate.replace('__ID__', id), '_blank');
                        element.innerHTML = `<i class="fas fa-file-pdf"></i>`;
                    } else if(type == 'docx') {
                        let urlGenerate = '{{ route('surat-tugas-promotor.generate-word', ['id' => '__ID__']) }}';
                        window.open(urlGenerate.replace('__ID__', id), '_blank');
                        element.innerHTML = `<i class="fas fa-file-word"></i>`;
                    }
                    btnPDF.removeAttribute('disabled');
                    btnDocx.removeAttribute('disabled');
                } else if (data.status === 'processing') {
                    alertField.innerHTML = `
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            ${data.message || 'File sedang dalam proses generate, mohon tunggu...'}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    `;
                    // Subscribe to updates for this file
                    subscribeToSuratUpdates(id);
                    element.innerHTML = `<i class="fas fa-file-${type.replace('docx', 'word')}"></i>`;
                    btnPDF.removeAttribute('disabled');
                    btnDocx.removeAttribute('disabled');
                } else {
                    alertField.innerHTML = `
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            ${data.message || 'Sepertinya file untuk surat ini hilang, sabar yaa masih di generate ulang kok!'}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    `;
                    generateFile(id);
                    element.innerHTML = `<i class="fas fa-file-${type.replace('docx', 'word')}"></i>`;
                    btnPDF.removeAttribute('disabled');
                    btnDocx.removeAttribute('disabled');
                }
            }).fail(function() {
                alertField.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Terjadi kesalahan saat memeriksa file.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                element.innerHTML = `<i class="fas fa-file-${type.replace('docx', 'word')}"></i>`;
                btnPDF.removeAttribute('disabled');
                btnDocx.removeAttribute('disabled');
            });
        }

        // Auto-subscribe to processing items when page loads
        $(window).on('load', function() {
            setTimeout(() => {
                subscribeToExistingProcessing();
            }, 1000);
        });
    </script>

    <style>
        .badge {
            font-size: 0.9rem;
        }

        .hapus-lokasi {
            font-size: 0.8rem;
        }

        .gap-2>* {
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }

        /* Status badge animations */
        .badge-warning {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
    </style>
@endsection
