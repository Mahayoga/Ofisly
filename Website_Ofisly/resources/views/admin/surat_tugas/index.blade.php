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
              <th>No</th>
              <th>No. Surat</th>
              <th>Nama Kandidat</th>
              <th>Tanggal Penugasan</th>
              <th>Tanggal Pembuatan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="tbody">
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
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
</main>
@endsection

@section('scripts')
  <script>
    function getData() {
      let xhttp = new XMLHttpRequest();

      xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
          let data = JSON.parse(this.responseText);
          let tbody = document.getElementById('tbody');
          let i = 1;
          tbody.innerHTML = '';
          data.forEach(element => {
            tbody.innerHTML += `
              <tr>
                <td>${i}</td>
                <td>${element.no_surat}</td>
                <td>${element.nama_kandidat}</td>
                <td>${element.tgl_penugasan}</td>
                <td>${element.tgl_surat_pembuatan}</td>
                <td>
                  <div class="btn btn-info"><span class="material-symbols-outlined text-white">preview</span></div>
                  <div class="btn btn-warning"><span class="material-symbols-outlined text-white">edit_square</span></div>
                  <div class="btn btn-danger"><span class="material-symbols-outlined">delete</span></div>
                </td>
              </tr>
            `;
            i++;
          });
        }
      };

      xhttp.open('GET', '{{ route('surat-tugas.getData') }}', true)
      xhttp.send();
    }

    function addData() {
      
    }
    getData();
  </script>
@endsection
