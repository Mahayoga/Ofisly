@extends('admin/layout/app')
@section('title', 'Dashboard')
@section('content')

<div class="container-fluid">

    {{-- Heading --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    {{-- Card surat tugas --}}
    <div class="row mb-4">
        <div class="col-auto pe-1">
            <div class="card border-left-primary shadow-sm toggle-card active" data-target="surat-content"
                style="width: 200px; height: 80px; cursor: pointer;">
                <div class="card-body p-2 d-flex align-items-center">
                    <i class="fas fa-file-alt text-primary fa-lg"></i>
                    <div style="margin-left: 13px">
                        <div class="font-weight-bold text-primary mb-0">Surat Tugas</div>
                        <small class="text-muted"> Total : {{ $totalSuratTugas }} </small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card User --}}
        <div class="col-auto ps-1">
            <div class="card border-left-success shadow-sm toggle-card" data-target="user-content"
                style="width: 200px; height: 80px; cursor: pointer;">
                <div class="card-body p-2 d-flex align-items-center">
                    <i class="fas fa-users text-success fa-lg"></i>
                    <div style="margin-left: 13px">
                        <div class="font-weight-bold text-success mb-0">User</div>
                        <small class="text-muted">Total : {{ $totalUser }} </small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Lowongan --}}
        <div class="col-auto ps-1">
            <div class="card border-left-danger shadow-sm toggle-card" data-target="lowongan-content"
                style="width: 200px; height: 80px; cursor: pointer;">
                <div class="card-body p-2 d-flex align-items-center">
                    <i class="fas fa-briefcase text-danger fa-lg"></i>
                    <div style="margin-left: 13px">
                        <div class="font-weight-bold text-danger mb-0">Lowongan</div>
                        <small class="text-muted">Total : {{ $totalLowongan }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Surat Tugas Content --}}

    <div class="row">
        {{-- chart --}}
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Surat Tugas</h6>
                    <div>
                        <span class="badge bg-primary text-white me-2"> Total Surat Tahun ini :
                            {{ $totalSuratTahunan }}</span>
                        <span class="badge bg-success text-white">Total Surat Bulan ini :
                            {{ $totalSuratBulanan }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div style="height: 320px; width: 100%;">
                        <canvas id="suratPerBulanChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- list Surat Tugas --}}
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-secondary text-white">Daftar Surat Tugas Terbaru</div>
                <div class="card-body">
                    <ul class="list-gorup list-group-flush">
                        @forelse($daftarSuratTugas as $surat)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $surat['type'] }}</span>
                                <span
                                    class="text-muted">{{ \Carbon\Carbon::parse($surat['tgl_surat_pembuatan'])->format('d M Y') }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">Tidak ada surat Tugas Terbaru</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>


    {{-- User Content --}}


    {{-- Lowongan Content --}}
    <div class="row">
        {{-- chart --}}
        <div class="row mt-4 align-items-center">


            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-danger">Statistik Lowongan Pekerjaan</h5>
                        <p class="text-muted mb-0">Menampilkan jumlah lowongan dan pendaftar selama tahun
                            {{ date('Y') }}</p>
                        <ul class="mt-3">
                            <li>Total Lowongan Tahun Ini: <strong>{{ $totalLowonganTahunan }}</strong></li>
                            <li>Total Pendaftar Tahun Ini: <strong>{{ $totalPendaftarTahunan }}</strong></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div style="height: 220px; width: 100%;">
                            <canvas id="lowonganChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// script surat tugas

    // chart surat tugas
    const ctx = document.getElementById('suratPerBulanChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                'Oktober', 'November', 'Desember'
            ],
            datasets: [{
                    label: 'Surat Promotor',
                    data: @json($totalSuratPromotorBulanan),
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: false
                },
                {
                    label: 'Surat Mandiri',
                    data: @json($totalSuratMandiriBulanan),
                    backgroundColor: 'rgba(54, 162, 235, 0.05)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: false
                },
                {
                    label: 'Surat Pengganti Driver',
                    data: @json($totalSuratPenggantiBulanan),
                    backgroundColor: 'rgba(255, 206, 86, 0.05)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return Number.isInteger(value) ? value : null;
                        }
                    },
                    beginAtZero: true
                }
            }
        }
    })



// Script Lowongan

    // Chart
    const ctxLowongan = document.getElementById('lowonganChart').getContext('2d');
    new Chart(ctxLowongan, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [
                {
                    label: 'Lowongan Dibuat',
                    data: @json($lowonganBulanan),
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgb(255, 99, 132)',
                    borderWidth: 1
                },
                {
                    label: 'Jumlah Pendaftar',
                    data: @json($pendaftarBulanan),
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgb(75, 192, 192)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return Number.isInteger(value) ? value : null;
                        }
                    },
                    beginAtZero: true
                }
            }
        
        }
    });
</script>

@endsection
