{{-- @extends('admin.layout.app')
@section('title', 'Surat Tugas Promotor')

@section('content')
    <div class="container-fluid">

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
        console.log(idGenerate)
        $.post(urlGenerate.replace('__ID__', idGenerate), {
          '_token': '{{ csrf_token() }}',
          'id': idGenerate
        }, function(data) {
          console.log(data);
        });
      });
    </script>
  @endif


@endsection --}}

@extends('admin.layout.app')
@section('title', 'Surat Tugas Promotor')

@section('content')
  <div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 text-gray-800">Data Surat Tugas Promotor</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item">Kumpulan data surat tugas untuk promotor</li>
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

      <div class="table-responsive">
        <table class="table table-bordered table-hover" id="suratTugasPromotorTable" width="100%" cellspacing="0">
          <thead class="bg-light">
            <tr>
              <th>No.</th>
              <th>Nama Kandidat</th>
              <th>Penempatan</th>
              <th>Tanggal Penugasan</th>
              <th>Tanggal Pembuatan</th>
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
              <td>{{ is_array($surat->penempatan) ? implode(', ', $surat->penempatan) : $surat->penempatan }}</td>
              <td class="tgl-penugasan">{{ \Carbon\Carbon::parse($surat->tgl_penugasan)->format('d/m/Y') }}</td>
              <td>{{ \Carbon\Carbon::parse($surat->tgl_surat_pembuatan)->format('d/m/Y') }}</td>
              <td class="text-center">
                <div class="btn-group">
                  <a href="{{ route('surat-tugas-promotor.generate-pdf', $surat->id_surat_tugas_promotor) }}" class="btn btn-sm btn-danger" target="_blank">
                    <i class="fas fa-file-pdf"></i>
                  </a>
                  <a href="{{ route('surat-tugas-promotor.generate-word', $surat->id_surat_tugas_promotor) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-file-word"></i>
                  </a>
                  <button class="btn btn-sm btn-info edit-btn" data-toggle="modal" data-target="#editModal" onclick="getDataEdit(this)" data-id="{{ $surat->id_surat_tugas_promotor }}">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button class="btn btn-sm btn-danger delete-btn" data-toggle="modal" data-target="#deleteModal" onclick="getDataHapus(this)" data-id="{{ $surat->id_surat_tugas_promotor }}">
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
                  <input type="text" class="form-control" id="nama_kandidat" name="nama_kandidat" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tgl_penugasan" class="form-label">Tanggal Penugasan</label>
                    <input type="date" placeholder="Pilih Tanggal" class="form-control" id="tgl_penugasan" name="tgl_penugasan" required>
                </div>
                <div class="col-md-12">
                  <label for="penempatan" class="form-label">Penempatan</label>
                  <input type="text" class="form-control" id="penempatan" name="penempatan" required placeholder="Contoh: Toko A, Toko B, Toko C">
                  <small class="form-text text-muted">Pisahkan setiap lokasi penempatan dengan koma (,).</small>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
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
                    <input type="text" class="form-control" id="edit_nama_kandidat" name="nama_kandidat" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="edit_tgl_penugasan" class="form-label">Tanggal Penugasan</label>
                    <input type="date" placeholder="Pilih Tanggal" class="form-control" id="edit_tgl_penugasan" name="tgl_penugasan" required>
                </div>
                <div class="col-md-12">
                    <label for="edit_penempatan" class="form-label">Penempatan</label>
                    <input type="text" class="form-control" id="edit_penempatan" name="penempatan" required>
                    <small class="form-text text-muted">Pisahkan setiap lokasi penempatan dengan koma (,).</small>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary" id="editSubmitBtn">
                <span id="editSubmitText">Simpan Perubahan</span>
                <span id="editSubmitSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
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
              Apakah Anda yakin ingin menghapus data Surat Tugas untuk Promotor <strong id="hapus_nama_kandidat"></strong>?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" id="deleteCancelBtn">Batal</button>
              <button type="submit" class="btn btn-danger" id="deleteSubmitBtn" disabled>
                <span id="deleteSubmitText">Hapus</span>
                <span id="deleteSubmitSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  @if (session('action') == 'generate_surat' && session('id_generate'))
    <script>
      $(document).ready(function() {
        let idGenerate = '{{ session('id_generate') }}';
        let urlGenerate = '{{ route("surat-tugas-promotor.generate-file") }}';
        $.post(urlGenerate, {
          '_token': '{{ csrf_token() }}',
          'id': idGenerate
        }, function(data, status) {
          console.log('Generate file response:', data);
        }).fail(function() {
          console.error('Failed to trigger file generation.');
        });
      });
    </script>
  @endif
  <script>
    $(document).ready(function(){
      let table = new DataTable('#suratTugasPromotorTable');

      // Inisialisasi flatpickr untuk modal create
      flatpickr("#tgl_penugasan", {
        minDate: 'today',
        dateFormat: "Y-m-d",
      });

      // Reset tombol hapus saat modal ditutup
      $('#deleteModal').on('hidden.bs.modal', function () {
        $('#deleteSubmitBtn').prop('disabled', true);
        $('#hapus_nama_kandidat').text('');
      });
    });

    function getDataEdit(element) {
      let idEdit = $(element).data('id');
      let urlEdit = '{{ route("surat-tugas-promotor.edit", ["id" => "__ID__"]) }}'.replace('__ID__', idEdit);
      let urlUpdate = '{{ route("surat-tugas-promotor.update", ["id" => "__ID__"]) }}'.replace('__ID__', idEdit);

      $.get(urlEdit, function(response) {
        if(response.success) {
          let data = response.data;
          $('#editForm').attr('action', urlUpdate);

          $('#edit_nama_kandidat').val(data.nama_kandidat);
          $('#edit_tgl_penugasan').val(data.tgl_penugasan.substring(0, 10));

          // Menggabungkan array penempatan menjadi string dengan koma
          let penempatanText = Array.isArray(data.penempatan) ? data.penempatan.join(', ') : data.penempatan;
          $('#edit_penempatan').val(penempatanText);

          // Inisialisasi flatpickr untuk modal edit
          flatpickr("#edit_tgl_penugasan", {
            dateFormat: "Y-m-d",
            defaultDate: data.tgl_penugasan
          });

        } else {
          alert('Gagal memuat data. Silakan coba lagi.');
        }
      }).fail(function() {
        alert('Terjadi kesalahan saat mengambil data.');
      });
    }

    function getDataHapus(element) {
      let idDelete = $(element).data('id');
      let namaKandidat = $(element).closest('tr').find('.nama-kandidat').text();
      let urlDelete = '{{ route("surat-tugas-promotor.destroy", ["id" => "__ID__"]) }}'.replace('__ID__', idDelete);

      $('#deleteForm').attr('action', urlDelete);
      $('#hapus_nama_kandidat').text(namaKandidat);
      $('#deleteSubmitBtn').prop('disabled', false);
    }
  </script>
@endsection
