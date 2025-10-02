@extends('admin.layout.app')

@section('title', 'Arsip Surat Tugas Driver')

@section('content')
  <div class="container-fluid">
    <h1 class="h3 text-gray-800">Arsip Surat Tugas Pengganti Driver</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item">Kumpulan surat yang sudah dipindahkan ke arsip</li>
    </ol>

    <div class="card border-0 mb-4">
      <div class="card-body border">
        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
        @endif

        @if(session('error'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
        @endif

        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="arsipDriverTable" width="100%" cellspacing="0">
            <thead class="bg-light">
              <tr>
                <th>No.</th>
                <th>Nama Kandidat</th>
                <th>Tanggal Penugasan</th>
                <th>Tanggal Pembuatan</th>
                <th>Status File</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
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
          <p>Apakah anda ingin keluarkan data ini dari arsip?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="button" onclick="editData(this)" class="btn btn-primary" id="editSubmitBtn" data-id="null">
            <span id="editSubmitText">Ya</span>
            <span id="editSubmitSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="editModalLabel">Hapus Permanen Surat Tugas</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span class="material-symbols-outlined">
              close
            </span>
          </button>
        </div>
        <div class="modal-body">
          <p>Apakah anda ingin hapus data ini secara permanen?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="button" onclick="deleteData(this)" class="btn btn-danger" id="deleteSubmitBtn" data-id="null">
            <span id="deleteSubmitText">Ya</span>
            <span id="deleteSubmitSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
          </button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script>
    let table = null
    $(document).ready(function () {
      table = new DataTable('#arsipDriverTable', {
        ajax: '{{ route('arsip-data-surat-tugas-driver.fetchRowData') }}',
        columns: [
          {
            data: null,
            render: function (data, type, row, meta) {
              return meta.row + 1; // row dimulai dari 0
            }
          },
          { data: 'nama_kandidat' },
          { 
            data: function(data, type, row) { // tgl_mulai_penugasan
              return data.tgl_mulai_penugasan.split('T')[0];
            }
          }, 
          { 
            data: function(data, type, row) { // tgl_mulai_penugasan
              return data.tgl_selesai_penugasan.split('T')[0];
            }
          },
          { 
            data: function(data, type, row) { // is_arsip
              if(data.is_arsip == 0) {
                return 'Tidak diarsipkan';
              } else if(data.is_arsip == 1) {
                return 'Diarsipkan';
              } else {
                return 'Status Tidak Valid!'
              }
            }
          },
          {
            data: null,
            render: function(data, type, row) {
              return `
                <div class="btn-group">
                  <button class="btn btn-sm btn-info edit-btn" data-toggle="modal" data-target="#editModal" onclick="getDataEdit(this)" data-id="${row.id_surat_tugas}">
                    <i class="fas fa-trash-restore"></i>
                  </button>
                  <button class="btn btn-sm btn-danger delete-btn" data-toggle="modal" data-target="#deleteModal" onclick="getDataHapus(this)" data-id="${row.id_surat_tugas}">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              `;
            }
          }
        ]
      });
    });

    function fetchRowData() {
      table.ajax.reload(null, false);
    }

    function getDataEdit(btn) {
      $('#editSubmitBtn').attr('data-id', btn.getAttribute('data-id'));
    }

    function getDataHapus(btn) {
      $('#deleteSubmitBtn').attr('data-id', btn.getAttribute('data-id'));
    }

    function editData(btn) {
      if(btn.getAttribute('data-id') == "null" || btn.getAttribute('data-id') == null) {
        Swal.fire({
          title: "Status Data",
          text: "Kesalahan terjadi, coba lagi nanti!",
          icon: "error"
        });
      } else {
        let urlUpdate = '{{ route('arsip-data-surat-tugas-driver.update', ['__ID__']) }}';
        $.post(urlUpdate.replace('__ID__', btn.getAttribute('data-id')), {
          '_token': '{{ csrf_token() }}',
          '_method': 'PUT',
          'id': btn.getAttribute('data-id')
        }, function(data, status) {
          if(data.status) {
            $('#editModal').modal('hide');
            Swal.fire({
              title: "Status Data",
              text: "Data berhasil dikeluarkan dari arsip!",
              icon: "success"
            });
            fetchRowData();
          } else {
            Swal.fire({
              title: "Status Data",
              text: "Kesalahan dari server!",
              icon: "error"
            });
            fetchRowData();
          }
        });
      }
      btn.setAttribute('data-id', 'null');
    }

    function deleteData(btn) {
      if(btn.getAttribute('data-id') == "null" || btn.getAttribute('data-id') == null) {
        Swal.fire({
          title: "Status Data",
          text: "Kesalahan terjadi, coba lagi nanti!",
          icon: "error"
        });
      } else {
        let urlDelete = '{{ route('arsip-data-surat-tugas-driver.destroy', ['__ID__']) }}';
        $.post(urlDelete.replace('__ID__', btn.getAttribute('data-id')), {
          '_token': '{{ csrf_token() }}',
          '_method': 'DELETE'
        }, function(data, status) {
          if(data.status) {
            $('#deleteModal').modal('hide');
            Swal.fire({
              title: "Status Data",
              text: "Data berhasil dihapus permanen!",
              icon: "success"
            });
            fetchRowData();
          } else {
            Swal.fire({
              title: "Status Data",
              text: "Kesalahan dari server!",
              icon: "error"
            });
            fetchRowData();
          }
        });
      }
      btn.setAttribute('data-id', 'null');
    }
  </script>
@endsection