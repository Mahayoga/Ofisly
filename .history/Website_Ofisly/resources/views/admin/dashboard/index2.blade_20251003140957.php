@extends('admin/layout/app')
@section('title', 'Dashboard')
@section('content')
<div class="container-fluid">


  {{-- <div class="container-fluid px-4">
    <h1 class="h3 text-gray-800">Maintenance</h1>

    <div class="text-center my-5">
      <h2 class="text-muted">🚧 Sedang Dalam Pengerjaan 🚧</h2>
      <p class="text-secondary">Fitur dashboard ini sedang kami kembangkan. Nantikan update selanjutnya!</p>
    </div>
  </div> --}}

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
  <div style="height: 320px; width: 100%;">
    <canvas id="suratPerBulanChart"></canvas>
  </div>

  {{-- User Content --}}
 

  {{-- Lowongan Content --}}




</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('suratPerBulanChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            datasets:[
                {
                    label: 'Surat Promotor',
                    data: @json($totalSuratPromotorBulanan),
                    borderColor: 'rgba(78, 115, 223, 1)',
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    tension: 0.3,
                    borderWidth: 2,
                    fill: false
                },
                {
                    label: 'Surat Mandiri',
                    data: @json($totalSuratMandiriBulanan),
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.05)',
                    tension: 0.3,
                    borderWidth: 2,
                    fill: false
                },
                {
                    label : 'Surat Pengganti Driver',
                    data: @json($totalSuratPenggantiDriverBulanan),
                    backgroundColor: 'rgba(255, 206, 86, 0.05)',
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
</script>



@endsection
