@extends('admin/layout/app')
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
              style="width: 180px; height: 80px; cursor: pointer;">
              <div class="card-body p-2 d-flex align-items-center">
                
                  <i class="fas fa-file-alt text-primary"></i>
                  <div>
                      <div class="font-weight-bold text-primary mb-0">Surat Tugas</div>
                      <small class="text-muted">{{ $totalSurat }} Total</small>
                  </div>
              </div>
          </div>
      </div>

      {{-- Card User --}}
      <div class="col-auto ps-1">
          <div class="card border-left-success shadow-sm toggle-card" data-target="user-content"
              style="width: 180px; height: 80px; cursor: pointer;">
              <div class="card-body p-2 d-flex align-items-center">
                  <i class="fas fa-users text-success"></i>
                  <div>
                      <div class="font-weight-bold text-success mb-0">User</div>
                      <small class="text-muted">{{ $totalUsers }} Total</small>
                  </div>
              </div>
          </div>
      </div>
  </div>

  {{-- Surat Tugas Content --}}
  <div id="surat-content" class="dashboard-content">
      <div class="card shadow mb-4">
          <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Daftar Surat Tugas Terbaru</h6>
          </div>
          <div class="card-body p-0">
              <ul class="list-group list-group-flush">
                  @forelse($suratTerbaru as $s)
                      <li class="list-group-item">
                          <div class="small text-muted">
                              {{ \Carbon\Carbon::parse($s->tgl_surat_pembuatan)->format('d M Y') }}</div>
                          <strong>{{ $s->no_surat }}</strong><br>
                          <span>{{ $s->nama_kandidat }}</span><br>
                          <small>Tgl Penugasan:
                              {{ \Carbon\Carbon::parse($s->tgl_penugasan)->format('d M Y') }}</small>
                          <div class="mt-2 text-end">
                              <a href="{{ url('/surat-tugas/' . $s->id_surat_tugas) }}"
                                  class="btn btn-sm btn-info">Detail</a>
                          </div>
                      </li>
                  @empty
                      <li class="list-group-item text-center text-muted">Belum ada surat tugas</li>
                  @endforelse
              </ul>
          </div>
      </div>
  </div>

  {{-- User Content --}}
  <div id="user-content" class="dashboard-content" style="display: none;">
      <div class="card shadow mb-4">
          <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Daftar User Terbaru</h6>
          </div>
          <div class="card-body p-0">
              <ul class="list-group list-group-flush">
                  @forelse($usersTerbaru as $user)
                      <li class="list-group-item">
                          <div class="d-flex justify-content-between align-items-center">
                              <div>
                                  <strong>{{ $user->name }}</strong><br>
                                  <small class="text-muted">{{ $user->email }}</small>
                              </div>
                              <div class="text-end">
                                  <small class="text-muted">Bergabung:
                                      {{ $user->created_at->format('d M Y') }}</small>
                              </div>
                          </div>
                      </li>
                  @empty
                      <li class="list-group-item text-center text-muted">Belum ada user</li>
                  @endforelse
              </ul>
          </div>
      </div>
  </div>
</div>

<script>
    document.querySelectorAll('.toggle-card').forEach(card => {
        card.addEventListener('click', function() {
            const target = this.getAttribute('data-target');

            document.querySelectorAll('.dashboard-content').forEach(content => {
                content.style.display = 'none';
            });

            document.getElementById(target).style.display = 'block';
            document.querySelectorAll('.toggle-card').forEach(c => {
                c.classList.remove('border-primary', 'border-success');
            });

            this.classList.add(target === 'surat-content' ? 'border-primary' : 'border-success');
        });
    });

    document.querySelector('.toggle-card[data-target="surat-content"]').classList.add('border-primary');
</script>

<style>
    .toggle-card {
        transition: all 0.2s ease;
        border-radius: 6px;
    }

    .toggle-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1) !important;
    }

    .toggle-card.active {
        border-left-width: 4px !important;
        background-color: #f8f9fa;
    }
</style>
@endsection
