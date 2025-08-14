@extends('admin.layout.app')
@section('title', 'Surat Tugas Pengganti Driver')

@section('content')
  <div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 text-gray-800">Data Surat Tugas Pengganti Driver</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item">Kumpulan data surat tugas</li>
    </ol>
    <div class="card border-0 mb-4">
      <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal">
        <i class="fas fa-plus"></i> Tambah Surat
        </button>
        <button type="button" class="btn btn-info" id="nyobaAjax">Coba ini</button>
      </div>
      <div class="card-body border">
        @if (session('success'))
          {{-- @php dd(session('action') == 'generate_surat') @endphp --}}
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
        <table class="table table-bordered table-hover" id="suratTugasTable" width="100%" cellspacing="0">
          <thead class="bg-light">
            <tr>
              <th>No.</th>
              <th>Nama Kandidat</th>
              <th>Tanggal Penugasan</th>
              <th>Tanggal Pembuatan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
          @php
            $i = 1;
          @endphp
          @foreach ($suratTugas as $surat)
            <tr data-id="{{ $surat->id_surat_tugas }}">
              <td>{{ $i }}</td>
              <td class="nama-kandidat">{{ $surat->nama_kandidat }}</td>
              <td class="tgl-penugasan">{{ \Carbon\Carbon::parse($surat->tgl_mulai_penugasan)->format('d/m/Y') }}</td>
              <td>{{ \Carbon\Carbon::parse($surat->tgl_surat_pembuatan)->format('d/m/Y') }}</td>
              <td class="text-center">
                <div class="btn-group">
                  <a href="{{ route('surat-tugas.generate-pdf', $surat->id_surat_tugas) }}" class="btn btn-sm btn-danger" target="_blank">
                    <i class="fas fa-file-pdf"></i>
                  </a>
                  <a href="{{ route('surat-tugas.generate-word', $surat->id_surat_tugas) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-file-word"></i>
                  </a>
                  <button class="btn btn-sm btn-info edit-btn" data-toggle="modal" data-target="#editModal" onclick="getDataEdit(this)" data-id="{{ $surat->id_surat_tugas }}">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button class="btn btn-sm btn-danger delete-btn" data-toggle="modal" data-target="#deleteModal" onclick="getDataHapus(this)" data-id="{{ $surat->id_surat_tugas }}">
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
            <h5 class="modal-title" id="createModalLabel">Buat Surat Tugas Baru</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="createForm" action="{{ route('surat-tugas.store') }}" method="POST">
            @csrf
            {{-- 
              'nama_kandidat',
              'nik_kandidat',
              'jabatan_kandidat',
              'nama_pengganti_kandidat',
              'tgl_mulai_penugasan',
              'tgl_selesai_penugasan',
              'tgl_surat_pembuatan',
              'status',
              'created_by',
              'file_path',
            --}}
            <div class="modal-body">
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="nama_kandidat" class="form-label">Nama Kandidat</label>
                  <input type="text" class="form-control" id="nama_kandidat" name="nama_kandidat" required>
                </div>
                <div class="col-md-6">
                  <label for="nik_kandidat" class="form-label">NIK Kandidat</label>
                  <input type="text" class="form-control" id="nik_kandidat" name="nik_kandidat" required>
                </div>
                <div class="col-md-6">
                  <label for="jabatan_kandidat" class="form-label">Jabatan Kandidat</label>
                  <input type="text" class="form-control" id="jabatan_kandidat" name="jabatan_kandidat" required>
                </div>
                <div class="col-md-6">
                  <label for="nama_pengganti_kandidat" class="form-label">Nama Pengganti Kandidat</label>
                  <input type="text" class="form-control" id="nama_pengganti_kandidat" name="nama_pengganti_kandidat" required>
                </div>
                <div class="col-md-6">
                  <label for="tgl_mulai_penugasan" class="form-label">Tanggal Mulai Penugasan</label>
                  <input type="date" placeholder="Pilih" class="form-control" id="tgl_mulai_penugasan" name="tgl_mulai_penugasan" required min="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-6">
                  <label for="tgl_selesai_penugasan" class="form-label">Tanggal Selesai Penugasan</label>
                  <input type="date" placeholder="Silahkan pilih tgl mulai penugasan dahulu" class="form-control" id="tgl_selesai_penugasan" name="tgl_selesai_penugasan" required disabled min="{{ date('Y-m-d') }}">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-info" id="clearTgl">Clear tanggal</button>
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
            <h5 class="modal-title" id="editModalLabel">Edit Surat Tugas</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="editForm" action="{{ route('surat-tugas.update', ['id' => '__ID__']) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="edit_nama_kandidat" class="form-label">Nama Kandidat</label>
                  <input type="text" class="form-control" id="edit_nama_kandidat" name="edit_nama_kandidat" required>
                </div>
                <div class="col-md-6">
                  <label for="edit_nik_kandidat" class="form-label">NIK Kandidat</label>
                  <input type="text" class="form-control" id="edit_nik_kandidat" name="edit_nik_kandidat" required>
                </div>
                <div class="col-md-6">
                  <label for="edit_jabatan_kandidat" class="form-label">Jabatan Kandidat</label>
                  <input type="text" class="form-control" id="edit_jabatan_kandidat" name="edit_jabatan_kandidat" required>
                </div>
                <div class="col-md-6">
                  <label for="edit_nama_pengganti_kandidat" class="form-label">Nama Pengganti Kandidat</label>
                  <input type="text" class="form-control" id="edit_nama_pengganti_kandidat" name="edit_nama_pengganti_kandidat" required>
                </div>
                <div class="col-md-6">
                  <label for="edit_tgl_mulai_penugasan" class="form-label">Tanggal Mulai Penugasan</label>
                  <input type="date" placeholder="Pilih" class="form-control" id="edit_tgl_mulai_penugasan" name="edit_tgl_mulai_penugasan" required min="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-6">
                  <label for="edit_tgl_selesai_penugasan" class="form-label">Tanggal Selesai Penugasan</label>
                  <input type="date" placeholder="Silahkan pilih tgl mulai penugasan dahulu" class="form-control" id="edit_tgl_selesai_penugasan" name="edit_tgl_selesai_penugasan" required min="{{ date('Y-m-d') }}">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary" id="editSubmitBtn">
                <span id="editSubmitText">Simpan</span>
                <span id="editSubmitSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="editModalLabel">Edit Surat Tugas</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="deleteForm" action="{{ route('surat-tugas.destroy', ['id' => '__ID__']) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body">
              Apakah anda akan menghapus data Surat Tugas untuk Driver <span id="hapus_nama_driver"></span>?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" id="deleteCancelBtn">Batal</button>
              <button type="submit" class="btn btn-danger" id="deleteSubmitBtn" disabled>
                <span id="editSubmitText">Hapus</span>
                <span id="editSubmitSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  @if (session('action'))
    <script>
      console.log('Masuk brokk')
      $(document).ready(function() {
        let idGenerate = '{{ session('id_generate') }}';
        let urlGenerate = '{{ route('surat-tugas.generate-file') }}'
        $.post(urlGenerate.replace('__ID__', idGenerate), {
          '_token': '{{ csrf_token() }}',
          'id': idGenerate
        }, function(data, status) {
          console.log(data);
        });
      });
    </script>
  @endif
  <script>
    let tgl_penugasan_value = null;
    let tgl_penugasan_edit_value = null;
    $(document).ready(function(){
      let table = new DataTable('#suratTugasTable');
      let stateTglMulai = flatpickr("#tgl_mulai_penugasan", {
        minDate: 'today',
        onChange: function(selectedDates, dateStr, instance) {
          tgl_penugasan_value = selectedDates[0];
        }
      });
      let stateTglSelesai = flatpickr("#tgl_selesai_penugasan", {});

      $('#tgl_mulai_penugasan').on('change', function() {
        if(tgl_penugasan_value != null) {
          let tempDateMulai = tgl_penugasan_value.getFullYear() + "-" + (tgl_penugasan_value.getMonth() + 1) + "-" + tgl_penugasan_value.getDate();
          stateTglSelesai.config.minDate = tempDateMulai;
          $('#tgl_selesai_penugasan').removeAttr('disabled');
          $('#tgl_selesai_penugasan').attr('placeholder', 'Pilih');
        } else {
          $('#tgl_selesai_penugasan').attr({
            'disabled': '',
            'placeholder': 'Silahkan pilih tgl mulai penugasan dahulu'
          });
        }
      });
      $('#clearTgl').on('click', function() {
        stateTglMulai.clear();
        stateTglSelesai.clear();
        $('#tgl_selesai_penugasan').attr({
          'disabled': '',
          'placeholder': 'Silahkan pilih tgl mulai penugasan dahulu'
        });
      });
      $('#deleteCancelBtn').on('click', function() {
        $('#deleteSubmitBtn').attr('disabled', '');
      });
    });

    function getDataEdit(element) {
      let idEdit = element.getAttribute('data-id');
      let urlEdit = '{{ route('surat-tugas.edit', ['id' => '__ID__']) }}'
      let urlUpdate = '{{ route('surat-tugas.update', ['id' => '__ID__']) }}'
      $.get(urlEdit.replace('__ID__', idEdit), function(data, status) {
        $('#editForm').removeAttr('action');
        $('#editForm').attr('action', urlUpdate.replace('__ID__', idEdit));
        let response = data;
        if(response.success) {
          let stateTglEditMulai = flatpickr('#edit_tgl_mulai_penugasan', {
            minDate: response.data.tgl_mulai_penugasan.substring(0, 10),
            onChange: function(selectedDates, dateStr, instance) {
              tgl_penugasan_edit_value = selectedDates[0];
            }
          });
          let stateTglEditSelesai = flatpickr('#edit_tgl_selesai_penugasan', {
            minDate: response.data.tgl_mulai_penugasan.substring(0, 10),
          });
          $('#edit_nama_kandidat').val(response.data.nama_kandidat);
          $('#edit_nik_kandidat').val(response.data.nik_kandidat);
          $('#edit_jabatan_kandidat').val(response.data.jabatan_kandidat);
          $('#edit_nama_pengganti_kandidat').val(response.data.nama_pengganti_kandidat);
          $('#edit_tgl_mulai_penugasan').val(response.data.tgl_mulai_penugasan.substring(0, 10));
          $('#edit_tgl_mulai_penugasan').on('change', function() {
            if(tgl_penugasan_edit_value != null) {
              let tempDateEditMulai = tgl_penugasan_edit_value.getFullYear() + "-" + (tgl_penugasan_edit_value.getMonth() + 1) + "-" + tgl_penugasan_edit_value.getDate();
              stateTglEditSelesai.config.minDate = tempDateEditMulai;
              $('#edit_tgl_selesai_penugasan').removeAttr('disabled');
              $('#edit_tgl_selesai_penugasan').attr('placeholder', 'Pilih');
            } else {
              $('#edit_tgl_selesai_penugasan').attr({
                'disabled': '',
                'placeholder': 'Silahkan pilih tgl mulai penugasan dahulu'
              });
            }
          });
          $('#edit_tgl_selesai_penugasan').val(response.data.tgl_selesai_penugasan.substring(0, 10));
        }
      });
    }

    function getDataHapus(element) {
      let idEdit = element.getAttribute('data-id');
      let urlEdit = '{{ route('surat-tugas.edit', ['id' => '__ID__']) }}'
      let urlDelete = '{{ route('surat-tugas.destroy', ['id' => '__ID__']) }}'
      $.get(urlEdit.replace('__ID__', idEdit), function(data, status) {
        $('#deleteForm').removeAttr('action');
        $('#deleteForm').attr('action', urlDelete.replace('__ID__', idEdit));
        let response = data;
        if(response.success) {
          $('#hapus_nama_driver').text(response.data.nama_kandidat);
          $('#deleteSubmitBtn').removeAttr('disabled');
        }
      });
    }
  </script>
@endsection



{{-- @section('content')
  <main>
    <div class="container-fluid px-4">
    <h1 class="mt-4">Data Surat Tugas</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item active">Kumpulan data surat tugas</li>
    </ol>
    </div>
    <div class="card border-0 mb-4 px-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
      <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
      <i class="fas fa-plus"></i> Tambah Surat
      </button>
    </div>
    <div class="card-body border">
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
        {{ \Carbon\Carbon::parse($surat->tgl_penugasan)->format('d/m/Y') }}
        </td>
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
        <button class="btn btn-sm btn-info edit-btn" data-bs-toggle="modal" data-bs-target="#editModal"
        data-id="{{ $surat->id_surat_tugas }}">
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
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="createForm" action="{{ route('surat-tugas.store') }}" method="POST">
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
        <h5 class="modal-title" id="editModalLabel">Edit Surat Tugas</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
          <input type="text" class="form-control" id="edit_nama_kandidat" name="nama_kandidat" required>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
          <label for="edit_tgl_penugasan" class="form-label">Tanggal Penugasan</label>
          <input type="date" class="form-control" id="edit_tgl_penugasan" name="tgl_penugasan" required>
          </div>
        </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary" id="editSubmitBtn">
          <span id="editSubmitText">Simpan</span>
          <span id="editSubmitSpinner" class="spinner-border spinner-border-sm d-none" role="status"
          aria-hidden="true"></span>
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
    document.addEventListener('DOMContentLoaded', function () {
    const dataTable = new simpleDatatables.DataTable("#suratTugasTable", {
      searchable: true,
      fixedHeight: true,
      perPage: 10,
      labels: {
      placeholder: "Cari...",
      perPage: "Data per halaman",
      noRows: "Data tidak ditemukan",
      info: "Menampilkan {end} dari {rows} data",
      }
    });

    // Handle create form submission
    document.getElementById('createForm').addEventListener('submit', function (e) {
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
    document.addEventListener('click', function (e) {
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
    document.getElementById('editForm').addEventListener('submit', function (e) {
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
    document.addEventListener('click', function (e) {
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
@endsection --}}