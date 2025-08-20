@extends('admin.layout.app')
@section('title', 'Lowongan Pekerjaan')

@section('content')
  <div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 text-gray-800">Data Lowongan Pekerjaan</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item">Kumpulan data lowongan pekerjaan</li>
    </ol>
    <div class="card border-0 mb-4">
      <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal">
          <i class="fas fa-plus"></i> Tambah Data
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
          <table class="table table-bordered table-hover" id="lowonganPekerjaanTable" width="100%" cellspacing="0">
            <thead class="bg-light">
              <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Gambar</th>
                <th>Tanggal Post</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @php $i = 1; @endphp
              @foreach ($lowonganPekerjaan as $lowongan)
                <tr data-id="{{ $lowongan->id_lowongan_pekerjaan }}">
                  <td>{{ $i }}</td>
                  <td>{{ $lowongan->judul }}</td>
                  <td class="deskripsi">{{ $lowongan->deskripsi }}</td>
                  <td>@if ($lowongan->gambar)
                          <img src="{{ asset('storage/' . $lowongan->gambar) }}" alt="Gambar Lowongan" width="100">
                      @else
                          -
                        @endif</td>  
                  <td>{{ $lowongan->tanggal_post }}</td>
                  <td class="text-center">
                    <div class="btn-group">
                      <button class="btn btn-sm btn-info edit-btn" data-toggle="modal" data-target="#editModal" onclick="getDataEdit(this)" data-id="{{ $lowongan->id_lowongan_pekerjaan }}">
                        <i class="fas fa-edit"></i>
                      </button>
                      <button class="btn btn-sm btn-danger delete-btn" data-toggle="modal" data-target="#deleteModal" onclick="getDataHapus(this)" data-id="{{ $lowongan->id_lowongan_pekerjaan }}">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                @php $i++; @endphp
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Create Modal -->
  <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="createModalLabel">Buat Lowongan Pekerjaan Baru</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="createForm" action="{{ route('lowongan-pekerjaan.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="judul" class="form-label">Judul</label>
                <input type="text" class="form-control" id="judul" name="judul" required>
              </div>
              <div class="col-md-6">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <input type="text" class="form-control" id="deskripsi" name="deskripsi" required>
              </div>
              <div class="col-md-6">
                <label for="gambar" class="form-label">Gambar</label>
                <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" required>
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
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="editModalLabel">Edit Data Lowongan Pekerjaan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="editForm" action="{{ route('lowongan-pekerjaan.update', ['lowongan_pekerjaan' => '__ID__']) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="edit_judul" class="form-label">Judul</label>
                <input type="text" class="form-control" id="edit_judul" name="edit_judul" required>
              </div>
              <div class="col-md-6">
                <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                <input type="text" class="form-control" id="edit_deskripsi" name="edit_deskripsi" required>
              </div>
              <div class="col-md-6">
                <label for="edit_gambar" class="form-label">Gambar</label>
                <input type="file" class="form-control" id="edit_gambar" name="edit_gambar" accept="image/*">
                <img id="preview_gambar" src="" alt="Preview" class="mt-2 d-none" width="150">
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
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="deleteModalLabel">Hapus Lowongan Pekerjaan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="deleteForm" action="{{ route('lowongan-pekerjaan.destroy', ['lowongan_pekerjaan' => '__ID__']) }}" method="POST">
          @csrf
          @method('DELETE')
          <div class="modal-body">
            Apakah anda akan menghapus data Lowongan Pekerjaan <span id="hapus_judul"></span>?
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
@endsection

@section('script')
  <script>
    $(document).ready(function(){
      new DataTable('#lowonganPekerjaanTable');
      $('#deleteCancelBtn').on('click', function() {
        $('#deleteSubmitBtn').attr('disabled', '');
      });
    });

    function getDataEdit(element) {
      let idEdit = element.getAttribute('data-id');
      let urlEdit   = '{{ route('lowongan-pekerjaan.edit', ['lowongan_pekerjaan' => '__ID__']) }}';
      let urlUpdate = '{{ route('lowongan-pekerjaan.update', ['lowongan_pekerjaan' => '__ID__']) }}';
      $.get(urlEdit.replace('__ID__', idEdit), function(data) {
        $('#editForm').attr('action', urlUpdate.replace('__ID__', idEdit));

        if(data.success) {
          $('#edit_judul').val(data.data.judul);
          $('#edit_deskripsi').val(data.data.deskripsi);
          if (data.data.gambar) {
            $('#preview_gambar').attr('src', '/storage/' + data.data.gambar).removeClass('d-none');
          } else {
            $('#preview_gambar').addClass('d-none');
          }
        }
      });
    }

    function getDataHapus(element) {
      let idEdit    = element.getAttribute('data-id');
      let urlEdit   = '{{ route('lowongan-pekerjaan.edit', ['lowongan_pekerjaan' => '__ID__']) }}';
      let urlDelete = '{{ route('lowongan-pekerjaan.destroy', ['lowongan_pekerjaan' => '__ID__']) }}';
      $.get(urlEdit.replace('__ID__', idEdit), function(data) {
        $('#deleteForm').attr('action', urlDelete.replace('__ID__', idEdit));
        if(data.success) {
          $('#hapus_judul').text(data.data.judul);
          $('#deleteSubmitBtn').removeAttr('disabled');
        }
      });
    }
  </script>
@endsection
