@extends('admin.layout.app')
@section('title', 'Lowongan Pekerjaan')

@section('content')
  <div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 text-gray-800">Data Pendaftar Lowongan Pekerjaan</h1>
    <ol class="breadcrumb mb-4">
      <li class="breadcrumb-item">Kumpulan data pendaftar lowongan pekerjaan</li>
    </ol>

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

    {{-- Loop per lowongan --}}
    @forelse ($pendaftar as $idLowongan => $listPendaftar)
      <div class="card border-0 mb-4">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">{{ $listPendaftar->first()->lowongan->judul }}</h5>
        </div>
        <div class="card-body border">
          <div class="table-responsive">
            <table class="table table-bordered table-hover" id="lowonganPekerjaanTable{{ $idLowongan }}" width="100%" cellspacing="0">
              <thead class="bg-light">
                <tr>
                  <th>No</th>
                  <th>Nama Pendaftar</th>
                  <th>Email</th>
                  <th>No. Telp</th>
                  <th>CV Pendaftar</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($listPendaftar as $i => $data)
                  <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $data->nama }}</td>
                    <td>{{ $data->email }}</td>
                    <td>{{ $data->no_telp }}</td>
                    <td>
                      @if ($data->cv)
                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#cvModal{{ $data->id_pendaftar }}">
                          Lihat CV
                        </button>
                        <a href="{{ asset('storage/' . $data->cv) }}" download class="btn btn-sm btn-primary mt-1">
                          Download
                        </a>

                        <!-- Modal CV -->
                        <div class="modal fade" id="cvModal{{ $data->id_pendaftar }}" tabindex="-1" role="dialog">
                          <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title">CV {{ $data->nama }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body text-center">
                                @php $extension = pathinfo($data->cv, PATHINFO_EXTENSION); @endphp
                                @if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                                  <img src="{{ asset('storage/' . $data->cv) }}" alt="CV {{ $data->nama }}" class="img-fluid rounded shadow">
                                @elseif (strtolower($extension) === 'pdf')
                                  <iframe src="{{ asset('storage/' . $data->cv) }}" width="100%" height="600px"></iframe>
                                @else
                                  <p>File tidak dapat ditampilkan di sini. Silakan unduh untuk melihat.</p>
                                @endif
                              </div>
                            </div>
                          </div>
                        </div>
                      @else
                        <span class="text-muted">Tidak ada CV</span>
                      @endif
                    </td>
                    <td>
                      @if ($data->status == 'Pending')
                        <div class="btn btn-sm btn-warning">{{ $data->status }}</div>
                      @elseif ($data->status == 'Diterima')
                        <div class="btn btn-sm btn-success">{{ $data->status }}</div>
                      @elseif ($data->status == 'Ditolak')
                        <div class="btn btn-sm btn-danger">{{ $data->status }}</div>
                      @else
                        <div class="btn btn-sm btn-warning">Pending?</div>
                      @endif
                    </td>
                    <td>
                      <div class="btn-group">
                        <button class="btn btn-sm btn-info edit-btn" data-toggle="modal" data-target="#editModal" onclick="getDataEdit(this)" data-id="{{ $data->id_pendaftar }}">
                          <i class="fas fa-edit"></i>
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
    @empty
      <div class="card border-0 mb-4">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">Tidak ada data</h5>
        </div>
        <div class="card-body border">
          Tidak ada data
        </div>
      </div>
    @endforelse
  </div>

  <!-- Edit Modal (tetap sama) -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="createModalLabel">Buat Lowongan Pekerjaan Baru</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="editForm" action="{{ route('pendaftar-lowongan.update', ['pendaftar_lowongan' => '__ID__']) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="row mb-3">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                    <label for="">Nama Pendaftar</label>
                    <input class="form-control" type="text" id="nama_pendaftar" disabled>
                  </div>
                  <div class="col-md-6">
                    <label for="">Mendaftar di lowongan:</label>
                    <input class="form-control" type="text" id="lowongan_dituju" disabled>
                  </div>
                </div>
              </div>
              <div class="col-md-12 mt-4">
                <div class="row">
                  <div class="col-md-6">
                    <label for="">Status Pendaftaran</label>
                    <select class="form-control" name="status_pendaftaran" id="status_pendaftaran">
                      <option value="not-set">Tidak diisi (Pending)</option>
                      <option value="Pending">Pending</option>
                      <option value="Diterima">Diterima</option>
                      <option value="Ditolak">Ditolak</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-primary" id="createSubmitBtn">
                    <span id="createSubmitText">Simpan</span>
                    <span id="createSubmitSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script>
    $(document).ready(function(){
      // aktifkan DataTable per tabel lowongan
      @foreach ($pendaftar as $idLowongan => $listPendaftar)
        new DataTable('#lowonganPekerjaanTable{{ $idLowongan }}');
      @endforeach
    });

    function getDataEdit(element) {
      let idEdit = element.getAttribute('data-id');
      let urlEdit   = '{{ route('pendaftar-lowongan.edit', ['pendaftar_lowongan' => '__ID__']) }}';
      let urlUpdate = '{{ route('pendaftar-lowongan.update', ['pendaftar_lowongan' => '__ID__']) }}';
      $.get(urlEdit.replace('__ID__', idEdit), function(data) {
        $('#editForm').attr('action', urlUpdate.replace('__ID__', idEdit));
        if(data.status) {
          $('#nama_pendaftar').val(data.dataPendaftar.nama);
          $('#lowongan_dituju').val(data.dataPendaftar.lowongan.judul);
          $('#status_pendaftaran').val(data.dataPendaftar.status);
        }
      });
    }
  </script>
@endsection
