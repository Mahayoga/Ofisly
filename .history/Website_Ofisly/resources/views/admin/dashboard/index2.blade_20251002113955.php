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
  

  {{-- User Content --}}
 

  {{-- Lowongan Content --}}


</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.querySelectorAll('.toggle-card').forEach(card => {
        card.addEventListener('click', function() {
            const target = this.getAttribute('data-target');

            document.querySelectorAll('.dashboard-content').forEach(content => {
                content.style.display = 'none';
            });

            document.getElementById(target).style.display = 'block';

            document.querySelectorAll('.toggle-card').forEach(c => {
                c.classList.remove(
                    'border-primary', 
                    'border-success', 
                    'border-danger',
                    'active'
                );
            });

            let borderClass;
            if (target === 'surat-content') {
                borderClass = 'border-primary';
            } else if (target === 'user-content') {
                borderClass = 'border-success';
            } else if (target === 'lowongan-content') {
                borderClass = 'border-danger';
            }
            
            this.classList.add(borderClass, 'active');
        });
    });

    document.querySelector('.toggle-card[data-target="surat-content"]').classList.add('border-primary', 'active');







    // chart dummy surat tugas
    new Chart(
        document.getElementById('SuratTugasChart'),
        {
            type: 'line',
            data: {
                labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Aug','Spt','Oct','Nov','Dec'],
                datasets: [{
                    data: [8,12,7,9,11,13],
                    borderColor: '#4e73df',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: false
                }]
            },
            options: {
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { display: false },
                    x: { 
                        grid: { display: false },
                        ticks: { color: '#858796' }
                    }
                },
                responsive: true
            }
        }
    );
</script>

<style>
    .toggle-card {
        transition: all 0.2s ease;
        border-radius: 6px;
    }

    .toggle-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1) !important;
        border-left-width: 3px !important;
    }

    .toggle-card.active {
        border-left-width: 4px !important;
        background-color: #f8f9fa;
    }

    #SuratTugasChart {
      width: 100% !important;
    }

</style>
@endsection
