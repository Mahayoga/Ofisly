{{-- @extends('admin.layout.app')
@section('title', 'Surat Penempatan Pengganti Driver Mandiri')

@section('content')
  <div class="container-fluid">
    <h1 class="h3 text-gray-800">Data Surat Tugas Mandiri</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item">Kumpulan data surat Tugas mandiri</li>
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
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
        @endif

        @if (session('error'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
        @endif

        @if (session('delete_success'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('delete_success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
        @endif

        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="suratPenempatanTable" width="100%" cellspacing="0">
            <thead class="bg-light">
              <tr>
                <th>No.</th>
                <th>Nomor Surat</th>
                <th>Nama Kandidat</th>
                <th>Jabatan</th>
                <th>Tanggal Pembuatan</th>
                <th>Tanggal Penempatan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @php
              $i = 1;
              @endphp
              @foreach ($suratPenempatan as $surat)
                <tr data-id="{{ $surat->id_surat_penempatan }}">
                  <td>{{ $i }}</td>
                  <td class="nomor-surat">{{ $surat->nomor_surat }}</td>
                  <td class="nama-kandidat">{{ $surat->nama_kandidat }}</td>
                  <td class="jabatan-kandidat">{{ $surat->jabatan_kandidat }}</td>
                  <td>{{ \Carbon\Carbon::parse($surat->tgl_surat_pembuatan)->format('d/m/Y') }}</td>
                  <td class="tgl-penempatan">{{ \Carbon\Carbon::parse($surat->tgl_mulai_penempatan)->format('d/m/Y') }}</td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="{{ route('surat-tugas-mandiri.generate-pdf', $surat->id_surat_penempatan) }}" class="btn btn-sm btn-danger" target="_blank">
                        <i class="fas fa-file-pdf"></i>
                      </a>
                      <a href="{{ route('surat-tugas-mandiri.generate-word', $surat->id_surat_penempatan) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-file-word"></i>
                      </a>
                      <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editModal" onclick="getDataEdit(this)" data-id="{{ $surat->id_surat_penempatan }}">
                        <i class="fas fa-edit"></i>
                      </button>
                      <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" onclick="getDataHapus(this)" data-id="{{ $surat->id_surat_penempatan }}">
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
    <div class="modal fade" id="createModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Buat Surat Penempatan Baru</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <form id="createForm" action="{{ route('surat-tugas-mandiri.store') }}" method="POST">
            @csrf
            <div class="modal-body">
              <div class="row mb-3">
                <div class="col-md-6">
                <label>Nomor Surat</label>
                  <input type="text" class="form-control" id="nomor_surat" name="nomor_surat" value="{{ $newNomor ?? '' }}">
                </div>
                <div class="col-md-6">
                  <label>Nama Kandidat</label>
                  <input type="text" class="form-control" id="nama_kandidat" name="nama_kandidat" required>
                </div>
                <div class="col-md-6">
                  <label>Jabatan Kandidat</label>
                  <select name="jabatan_kandidat" id="jabatan_kandidat" class="form-control" required>
                    <option value="">-- Pilih Jabatan --</option>
                    <option value="Driver">Driver</option>
                    <option value="Pramubakti">Pramubakti</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label>Tanggal Mulai Penempatan</label>
                  <input type="date" class="form-control" id="tgl_mulai_penempatan" name="tgl_mulai_penempatan" required min="{{ date('Y-m-d') }}">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-info" id="clearTgl">Clear tanggal</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary" id="createSubmitBtn">
                <span id="createSubmitText">Simpan</span>
                <span id="createSubmitSpinner" class="spinner-border spinner-border-sm d-none"></span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Edit Surat Penempatan</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <form id="editForm" action="{{ route('surat-tugas-mandiri.update', ['surat_tugas_mandiri' => '__ID__']) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
              <div class="row mb-3">
                <div class="col-md-6">
                  <label>Nomor Surat</label>
                  <input type="text" class="form-control" id="edit_nomor_surat" name="edit_nomor_surat" required>
                </div>
                <div class="col-md-6">
                  <label>Nama Kandidat</label>
                  <input type="text" class="form-control" id="edit_nama_kandidat" name="edit_nama_kandidat" required>
                </div>
                <div class="col-md-6">
                  <label>Jabatan Kandidat</label>
                  <select name="edit_jabatan_kandidat" id="edit_jabatan_kandidat" class="form-control" required>
                    <option value="">-- Pilih Jabatan --</option>
                    <option value="Driver">Driver</option>
                    <option value="Pramubakti">Pramubakti</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label>Tanggal Mulai Penempatan</label>
                  <input type="date" class="form-control" id="edit_tgl_mulai_penempatan" name="edit_tgl_mulai_penempatan" required>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary" id="editSubmitBtn">
                <span id="editSubmitText">Simpan</span>
                <span id="editSubmitSpinner" class="spinner-border spinner-border-sm d-none"></span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Hapus Surat Penempatan</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <form id="deleteForm" action="{{ route('surat-tugas-mandiri.destroy', ['surat_tugas_mandiri' => '__ID__']) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body">
              Apakah anda akan menghapus data Surat Penempatan untuk <span id="hapus_nama_kandidat"></span>?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" id="deleteCancelBtn">Batal</button>
              <button type="submit" class="btn btn-danger" id="deleteSubmitBtn" disabled>
                <span>Hapus</span>
                <span id="editSubmitSpinner" class="spinner-border spinner-border-sm d-none"></span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  @if (session('action') == 'generate_surat')
    <script>
      $(document).ready(function() {
        let idGenerate = '{{ session('id_generate') }}';
        let urlGenerate = '{{ route('surat-tugas-mandiri.generate-file') }}';
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

  <script>
    $(document).ready(function(){
      new DataTable('#suratPenempatanTable');
      flatpickr("#tgl_mulai_penempatan", {
        minDate: 'today'
      });
      $('#clearTgl').on('click', function() {
        document.querySelector("#tgl_mulai_penempatan")._flatpickr.clear();
      });
      $('#deleteCancelBtn').on('click', function() {
        $('#deleteSubmitBtn').attr('disabled', '');
      });
    });

    function getDataEdit(element) {
      let idEdit = element.getAttribute('data-id');
      let urlEdit = '{{ route('surat-tugas-mandiri.edit', ['surat_tugas_mandiri' => '__ID__']) }}';
      let urlUpdate = '{{ route('surat-tugas-mandiri.update', ['surat_tugas_mandiri' => '__ID__']) }}';
      $.get(urlEdit.replace('__ID__', idEdit), function(data) {
        $('#editForm').attr('action', urlUpdate.replace('__ID__', idEdit));
        if(data.success) {
          $('#edit_nomor_surat').val(data.data.nomor_surat);
          $('#edit_nama_kandidat').val(data.data.nama_kandidat);
          $('#edit_jabatan_kandidat').val(data.data.jabatan_kandidat);
          $('#edit_tgl_mulai_penempatan').val(data.data.tgl_mulai_penempatan.substring(0, 10));
        }
      });
    }

    function getDataHapus(element) {
      let idEdit = element.getAttribute('data-id');
      let urlEdit = '{{ route('surat-tugas-mandiri.edit', ['surat_tugas_mandiri' => '__ID__']) }}';
      let urlDelete = '{{ route('surat-tugas-mandiri.destroy', ['surat_tugas_mandiri' => '__ID__']) }}';
      $.get(urlEdit.replace('__ID__', idEdit), function(data) {
        $('#deleteForm').attr('action', urlDelete.replace('__ID__', idEdit));
        if(data.success) {
          $('#hapus_nama_kandidat').text(data.data.nama_kandidat);
          $('#deleteSubmitBtn').removeAttr('disabled');
        }
      });
    }
  </script>
@endsection --}}

@extends('admin.layout.app')
@section('title', 'Surat Tugas Pengganti Driver Mandiri')

@section('content')
  <a class="d-none" id="link-generate" href="" target="_blank"></a>
  <div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 text-gray-800">Data Surat Tugas Pengganti Driver Mandiri</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item">Kumpulan data surat tugas</li>
    </ol>
    <div class="card border-0 mb-4">
      <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal">
        <i class="fas fa-plus"></i> Tambah Surat
        </button>
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

      <div id="alert-field">
        
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-hover" id="suratTugasTable" width="100%" cellspacing="0">
          <thead class="bg-light">
            <tr>
              <th>No.</th>
              <th>Nomor Surat</th>
              <th>Nama Kandidat</th>
              <th>Jabatan</th>
              <th>Tanggal Pembuatan</th>
              <th>Tanggal Penempatan</th>
              <th>Status File</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id='tbody'>
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
              <span class="material-symbols-outlined">
                close
              </span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row mb-3">
              <div class="col-md-6">
                <label>Nomor Surat</label>
                <input type="text" class="form-control" id="nomor_surat" name="nomor_surat" value="{{ $newNomor ?? '' }}">
              </div>
              <div class="col-md-6">
                <label>Nama Kandidat</label>
                <input type="text" class="form-control" id="nama_kandidat" name="nama_kandidat" required>
              </div>
              <div class="col-md-6">
                <label>Jabatan Kandidat</label>
                <select name="jabatan_kandidat" id="jabatan_kandidat" class="form-control" required>
                  <option value="">-- Pilih Jabatan --</option>
                  <option value="Driver">Driver</option>
                  <option value="Pramubakti">Pramubakti</option>
                </select>
              </div>
              <div class="col-md-6">
                <label>Tanggal Mulai Penempatan</label>
                <input type="date" class="form-control" id="tgl_mulai_penempatan" name="tgl_mulai_penempatan" required min="{{ date('Y-m-d') }}">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-info" id="clearTgl">Clear tanggal</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" onclick="addData()" class="btn btn-primary" id="createSubmitBtn">
              <span id="createSubmitText">Simpan</span>
              <span id="createSubmitSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            </button>
          </div>
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
              <span class="material-symbols-outlined">
                close
              </span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row mb-3">
              <div class="col-md-6">
                <label>Nomor Surat</label>
                <input type="text" class="form-control" id="edit_nomor_surat" name="edit_nomor_surat" required>
              </div>
              <div class="col-md-6">
                <label>Nama Kandidat</label>
                <input type="text" class="form-control" id="edit_nama_kandidat" name="edit_nama_kandidat" required>
              </div>
              <div class="col-md-6">
                <label>Jabatan Kandidat</label>
                <select name="edit_jabatan_kandidat" id="edit_jabatan_kandidat" class="form-control" required>
                  <option value="">-- Pilih Jabatan --</option>
                  <option value="Driver">Driver</option>
                  <option value="Pramubakti">Pramubakti</option>
                </select>
              </div>
              <div class="col-md-6">
                <label>Tanggal Mulai Penempatan</label>
                <input type="date" class="form-control" id="edit_tgl_mulai_penempatan" name="edit_tgl_mulai_penempatan" required>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" onclick="editData()" class="btn btn-primary" id="editSubmitBtn">
              <span id="editSubmitText">Simpan</span>
              <span id="editSubmitSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            </button>
          </div>
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
              <span class="material-symbols-outlined">
                close
              </span>
            </button>
          </div>
          <form id="deleteForm" action="{{ route('surat-tugas-mandiri.destroy', ['surat_tugas_mandiri' => '__ID__']) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body">
              Apakah anda akan menghapus data Surat Tugas untuk Driver <span id="hapus_nama_kandidat"></span>?
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
  <script>
    let socket = null;
    let table = null;
    let tgl_penugasan_value = null;
    let tgl_penugasan_edit_value = null;
    let idEdit = null;
    let processedId = null;
    $(document).ready(function(){
      socket = io("{{ env('FLASK_API_URL') }}");
      table = new DataTable('#suratTugasTable', {
        ajax: '{{ route('surat-tugas-mandiri.fetchRowData') }}',
        columns: [
          /*
            <th>No.</th>
            <th>Nomor Surat</th>
            <th>Nama Kandidat</th>
            <th>Jabatan</th>
            <th>Tanggal Pembuatan</th>
            <th>Tanggal Penempatan</th>
            <th>Status File</th>
            <th>Aksi</th>
          */
          { 
            data: null,
            render: function (data, type, row, meta) {
              return meta.row + 1; // row dimulai dari 0
            }
          },
          { data: 'nomor_surat' },
          { data: 'nama_kandidat' },
          { data: 'jabatan_kandidat' },
          { 
            data: function(data, type, row) { // tgl_surat_pembuatan
              return data.tgl_surat_pembuatan.split('T')[0];
            }
          },
          { 
            data: function(data, type, row) { // tgl_mulai_penempatan
              return data.tgl_mulai_penempatan.split('T')[0];
            }
          },
          {
            data: null,
            render: function(data, type, row) {
              return `
                <span class="badge badge-info info-status-file" data-id="${row.id_surat_penempatan}">Memuat...</span>
              `;
            }
          },
          {
            data: null,
            render: function(data, type, row) {
              return `
                <div class="btn-group">
                  <button class="btn btn-sm btn-danger" id="btn_pdf_${row.id_surat_penempatan}" onclick="getInfoFile(this, '${row.id_surat_penempatan}', 'pdf')">
                    <i class="fas fa-file-pdf"></i>
                  </button>
                  <button class="btn btn-sm btn-primary" id="btn_word_${row.id_surat_penempatan}" onclick="getInfoFile(this, '${row.id_surat_penempatan}', 'docx')">
                    <i class="fas fa-file-word"></i>
                  </button>
                  <button class="btn btn-sm btn-info edit-btn" data-toggle="modal" data-target="#editModal" onclick="getDataEdit(this)" data-id="${row.id_surat_penempatan}">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button class="btn btn-sm btn-danger delete-btn" data-toggle="modal" data-target="#deleteModal" onclick="getDataHapus(this)" data-id="${row.id_surat_penempatan}">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              `;
            }
          }
        ]
      });
      let stateTglMulai = flatpickr("#tgl_mulai_penempatan", {
        minDate: 'today',
        onChange: function(selectedDates, dateStr, instance) {
          tgl_penugasan_value = selectedDates[0];
        }
      });

      $('#clearTgl').on('click', function() {
        stateTglMulai.clear();
      });
      
      $('#deleteCancelBtn').on('click', function() {
        $('#deleteSubmitBtn').attr('disabled', '');
      });

      table.on('xhr', function(e, settings, json, xhr) {
        socket.emit('connect_after_fetch_table', true);
        console.log('Connect After Fetch Table...');
      });

      socket.on("connect", () => {
        console.log("Terkoneksi dengan Socket.IO server!");
        let arrTable = document.querySelectorAll('.info-status-file');
        arrTable.forEach(element => {
          socket.emit('get_info_process', element.getAttribute('data-id'));
          console.log('Connecting and fetching status table (first time)...: ' + element.getAttribute('data-id'));
        });
      });
      socket.on("connect_after", () => {
        console.log("Terkoneksi dengan Socket.IO server (after)!");
        let arrTable = document.querySelectorAll('.info-status-file');
        arrTable.forEach(element => {
          socket.emit('get_info_process', element.getAttribute('data-id'));
          console.log('Get Info Process...: ' + element.getAttribute('data-id'));
        });
      });
      socket.on('fetch_status', (data) => {
        console.log('Fetch Status...: ' + data.id);
        document.querySelectorAll('.info-status-file').forEach(element => {
          if(element.getAttribute('data-id') == data.id && data.status) {
            element.removeAttribute('class');
            element.setAttribute('class', 'badge badge-success info-status-file');
            element.innerHTML = 'File siap';
            console.log('Fetch Status Done (File Siap): ' + element.getAttribute('data-id'));
            return;
          } else if(element.getAttribute('data-id') == data.id && !data.status) {
            if(element.getAttribute('data-id') == processedId) {
              console.log('File hilang sudah diatasi karena file masih proses generate!')
              processedId = null;
              return;
            }
            element.removeAttribute('class');
            element.setAttribute('class', 'badge badge-danger info-status-file');
            element.innerHTML = 'File hilang!';
            console.log('Fetch Status Done (File Hilang): ' + element.getAttribute('data-id'));
            console.log('Processed ID: ' + processedId);
            return;
          }
        });
      });
      socket.on("send_status_process", (msg) => {
        console.log('Send Status Process...: ' + msg.id);
        document.querySelectorAll('.info-status-file').forEach(element => {
          if(element.getAttribute('data-id') == msg.id && !msg.status) {
            element.removeAttribute('class');
            element.setAttribute('class', 'badge badge-success info-status-file');
            element.innerHTML = 'File siap';
            console.log('Send Status Process Done (File Siap): ' + element.getAttribute('data-id'));
            return;
          } else if(element.getAttribute('data-id') == msg.id && msg.status) {
            element.removeAttribute('class');
            element.setAttribute('class', 'badge badge-warning info-status-file');
            element.innerHTML = 'File masih dalam proses';
            console.log('Send Status Process Done (File dalam proses): ' + element.getAttribute('data-id'));
            return;
          }
        });
      });
    });

    // #TODO
    // FetchRowData
    // Form dibuah JS semua biar cocok sama socket

    function fetchRowData() {
      table.ajax.reload(null, false);
    }

    function addData() {
      Swal.fire({
        title: "Status Data",
        text: "Data sedang ditambahkan...",
        icon: "info"
      });
      let nomor_surat = document.getElementById('nomor_surat');
      let nama_kandidat = document.getElementById('nama_kandidat');
      let jabatan_kandidat = document.getElementById('jabatan_kandidat');
      let tgl_mulai_penempatan = document.getElementById('tgl_mulai_penempatan');
      $.post('{{ route('surat-tugas-mandiri.store') }}', {
        '_token': '{{ csrf_token() }}',
        'nomor_surat': nomor_surat.value,
        'nama_kandidat': nama_kandidat.value,
        'jabatan_kandidat': jabatan_kandidat.value,
        'tgl_mulai_penempatan': tgl_mulai_penempatan.value,
      }, function(data, status) {
        if(data.status) {
          $('#createModal').modal('hide');
          Swal.fire({
            title: "Status Data",
            text: "Data berhasil ditambahkan!",
            icon: "success"
          });
          processedId = data.id_generate;
          fetchRowData();
          let urlGenerate = '{{ route('surat-tugas-mandiri.generate-file') }}'
          $.post(urlGenerate, {
            '_token': '{{ csrf_token() }}',
            'id': data.id_generate
          }, function(data, status) {
            console.log(data);
          });
        }
      });
    }

    function editData() {
      if(idEdit != null) {
        Swal.fire({
          title: "Status Data",
          text: "Data sedang diedit...",
          icon: "info"
        });
        let nomor_surat = document.getElementById('edit_nomor_surat');
        let nama_kandidat = document.getElementById('edit_nama_kandidat');
        let jabatan_kandidat = document.getElementById('edit_jabatan_kandidat');
        let tgl_mulai_penempatan = document.getElementById('edit_tgl_mulai_penempatan');
        
        let urlUpdate = '{{ route('surat-tugas-mandiri.update', ['surat_tugas_mandiri' => '__ID__']) }}'
        $.post(urlUpdate.replace('__ID__', idEdit), {
          '_token': '{{ csrf_token() }}',
          '_method': 'PUT',
          'edit_nomor_surat': nomor_surat.value,
          'edit_nama_kandidat': nama_kandidat.value,
          'edit_jabatan_kandidat': jabatan_kandidat.value,
          'edit_tgl_mulai_penempatan': tgl_mulai_penempatan.value,
        }, function(data, status) {
          if(data.status) {
            $('#editModal').modal('hide');
            Swal.fire({
              title: "Status Data",
              text: "Data berhasil diedit!",
              icon: "success"
            });
            processedId = data.id_generate;
            console.log("ProcessedId in edit function: " + processedId);
            fetchRowData();
            let urlGenerate = '{{ route('surat-tugas-mandiri.generate-file') }}'
            $.post(urlGenerate, {
              '_token': '{{ csrf_token() }}',
              'id': data.id_generate
            }, function(data, status) {
              console.log("ini?", data);
            });
          }
        });
      } else {
        Swal.fire({
          title: "Status Data",
          text: "Kesalahan saat mengedit data!",
          icon: "error"
        });
      }
    }

    function getDataEdit(element) {
      idEdit = element.getAttribute('data-id');
      let urlEdit = '{{ route('surat-tugas-mandiri.edit', ['surat_tugas_mandiri' => '__ID__']) }}'
      let urlUpdate = '{{ route('surat-tugas-mandiri.update', ['surat_tugas_mandiri' => '__ID__']) }}'
      $.get(urlEdit.replace('__ID__', idEdit), function(data, status) {
        $('#editForm').removeAttr('action');
        $('#editForm').attr('action', urlUpdate.replace('__ID__', idEdit));
        let response = data;
        if(response.success) {
          let stateTglEditMulai = flatpickr('#edit_tgl_mulai_penempatan', {
            minDate: response.data.tgl_mulai_penempatan.substring(0, 10),
            onChange: function(selectedDates, dateStr, instance) {
              tgl_penugasan_edit_value = selectedDates[0];
            }
          });
          $('#edit_nomor_surat').val(response.data.nomor_surat);
          $('#edit_nama_kandidat').val(response.data.nama_kandidat);
          $('#edit_jabatan_kandidat').val(response.data.jabatan_kandidat);
          $('#edit_tgl_mulai_penempatan').val(response.data.tgl_mulai_penempatan.substring(0, 10));
        }
      });
    }

    function getDataHapus(element) {
      let idEdit = element.getAttribute('data-id');
      let urlEdit = '{{ route('surat-tugas-mandiri.edit', ['surat_tugas_mandiri' => '__ID__']) }}'
      let urlDelete = '{{ route('surat-tugas-mandiri.destroy', ['surat_tugas_mandiri' => '__ID__']) }}'
      $.get(urlEdit.replace('__ID__', idEdit), function(data, status) {
        $('#deleteForm').removeAttr('action');
        $('#deleteForm').attr('action', urlDelete.replace('__ID__', idEdit));
        let response = data;
        if(response.success) {
          $('#hapus_nama_kandidat').text(response.data.nama_kandidat);
          $('#deleteSubmitBtn').removeAttr('disabled');
        }
      });
    }
  
    function getInfoFile(element, id, type) {
      let alertField = document.getElementById('alert-field');
      element.innerHTML = `<i class="fas fa-spin fa-sync-alt"></i>`;
      let btnPDF = document.getElementById('btn_pdf_' + id);
      let btnDocx = document.getElementById('btn_word_' + id);

      btnPDF.setAttribute('disabled', '');
      btnDocx.setAttribute('disabled', '');
      
      let urlFileCheck = '{{ route('surat-tugas-mandiri.file-check', ['id' => '__ID__', 'type' => '__TYPE__']) }}';
      $.get(urlFileCheck.replace('__ID__', id).replace('__TYPE__', type), function(data, status) {
        if(data.status) {
          $('#link-generate').removeAttr('href');
          if(type == 'pdf') {
            console.log('Generate PDF...');
            let urlGenerate = '{{ route('surat-tugas-mandiri.generate-pdf', ['id' => '__ID__']) }}';
            $('#link-generate').attr('href', urlGenerate.replace('__ID__', id));
            document.getElementById('link-generate').click();
            element.innerHTML = `<i class="fas fa-file-pdf"></i>`;
            btnPDF.removeAttribute('disabled');
            btnDocx.removeAttribute('disabled');
          } else if(type == 'docx') {
            let urlGenerate = '{{ route('surat-tugas-mandiri.generate-word', ['id' => '__ID__']) }}';
            $('#link-generate').attr('href', urlGenerate.replace('__ID__', id));
            document.getElementById('link-generate').click();
            element.innerHTML = `<i class="fas fa-file-word"></i>`;
            btnPDF.removeAttribute('disabled');
            btnDocx.removeAttribute('disabled');
          }
        } else {
          alertField.innerHTML = `
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
              Sepertinya file untuk surat ini hilang, sabar yaa masih di generate ulang kok!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          `;
          let urlGenerate = '{{ route('surat-tugas-mandiri.generate-file') }}'
          $.post(urlGenerate, {
            '_token': '{{ csrf_token() }}',
            'id': id
          }, function(data, status) {
            if(data.status == 'success') {
              btnPDF.removeAttribute('disabled');
              btnDocx.removeAttribute('disabled');
              alertField.innerHTML = `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  Oke sudah tergenerate!
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              `;
            } else {
              alertField.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  Sepertinya error terjadi saat generate. Tenang, ini masalah di server, silahkan coba lagi nanti ya!
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              `;
              btnPDF.removeAttribute('disabled');
              btnDocx.removeAttribute('disabled');
            }
          });
          element.innerHTML = `<i class="fas fa-file-${type.replace('docx', 'word')}"></i>`;
        }
      });
    }
  </script>
@endsection
