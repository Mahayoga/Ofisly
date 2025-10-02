@extends('admin.layout.app')

@section('title', 'Arsip Surat Tugas Mandiri')

@section('content')
<div class="container-fluid">
    <h1 class="h3 text-gray-800">Arsip Surat Tugas Mandiri</h1>
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
                <table class="table table-bordered table-hover" id="arsipMandiriTable" width="100%" cellspacing="0">
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
                        @php $i = 1; @endphp
                        @forelse($arsipMandiri as $surat)
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ $surat->nomor_surat }}</td>
                            <td>{{ $surat->nama_kandidat }}</td>
                            <td>{{ $surat->jabatan_kandidat }}</td>
                            <td>{{ \Carbon\Carbon::parse($surat->tgl_surat_pembuatan)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($surat->tgl_mulai_penempatan)->format('d/m/Y') }}</td>
                            <td class="text-center">
                                {{-- Aksi akan diisi nanti --}}
                            </td>
                        </tr>
                        @php $i++; @endphp
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data arsip.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function(){
        new DataTable('#arsipMandiriTable');
    });
</script>
@endsection