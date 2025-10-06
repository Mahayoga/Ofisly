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
  <div class="card shadow-sm mb-4">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Surat Per Bulan</h6>
    </div>
    <div class="card-body">
        <canvas id="suratPerBulanChart"></canvas>
    </div>
  </div>

  {{-- User Content --}}
 

  {{-- Lowongan Content --}}


</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctxBulanan = document.getElementById('suratPerBulanChart').getContext('2d');
    const suratPerBulanChart = new Chart(ctxBulanan, {
        type: 'line',
        data: {
            labels: [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ],
            datasets: [{
                labels: 'Total Surat',
                data: @json(array_values($suratTugasBulanan)),
                border
            }]
        }
    });
</script>



@endsection
