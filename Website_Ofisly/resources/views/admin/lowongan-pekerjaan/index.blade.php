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
            <a href="{{ route('lowongan-pekerjaan.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Data
            </a>
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
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($lowonganPekerjaan as $lowongan)
                            <tr>
                                <td>{{ $i }}</td>
                                {{-- <td>{{ $lowongan->id_lowongan_pekerjaan}}</td> --}}
                                <td>{{ $lowongan->judul }}</td>
                                <td>{{ $lowongan->deskripsi }}</td>
                                <td>
                                    @if ($lowongan->gambar)
                                        <img src="{{ asset('storage/' . $lowongan->gambar) }}" alt="{{ $lowongan->judul }}" width="70">
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($lowongan->tanggal_post)->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('lowongan-pekerjaan.show',  ['lowongan_pekerjaan' => $lowongan->id_lowongan_pekerjaan]) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('lowongan-pekerjaan.edit', ['lowongan_pekerjaan' => $lowongan->id_lowongan_pekerjaan]) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form id="form-{{ $lowongan->id_lowongan_pekerjaan }}" action="{{ route('lowongan-pekerjaan.destroy', ['lowongan_pekerjaan' => $lowongan->id_lowongan_pekerjaan]) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger button-delete" data-id="{{ $lowongan->id_lowongan_pekerjaan }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
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
</div>
@endsection

{{-- @section('script')
<script>
    $(document).ready(function(){
        let table = new DataTable('#lowonganPekerjaanTable');
    });
</script>
@endsection --}}

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function(){
            $('#lowonganPekerjaanTable').DataTable();
            $('.button-delete').on('click', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batalkan'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#form-' + id).submit();
                    }
                });
            });
        });
    </script>
@endsection


