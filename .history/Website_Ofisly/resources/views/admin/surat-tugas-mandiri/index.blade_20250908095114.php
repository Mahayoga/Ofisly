@extends('admin.layout.app')
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
                <th>Status</th>
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
                  <td>
                    <span id="status-mandiri-{{ $surat->id_surat_penempatan }}">
                      
                    </span>
                  </td>
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
@endsection
