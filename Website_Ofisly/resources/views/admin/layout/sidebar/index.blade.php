<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
    <img src="{{ asset('assets/img/logo-ofisly-putih.png') }}"alt="Ofisly Logo"style="max-width: 120px; height: auto; display: block;">
    <div class="sidebar-brand-text mx-3">
    </div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <!-- Nav Item - Dashboard -->
  @if (Route::currentRouteName() == 'dashboard.index')
    <li class="nav-item active">
      <a class="nav-link" href="{{ route('dashboard.index') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
    </li>
  @else
    <li class="nav-item">
      <a class="nav-link" href="{{ route('dashboard.index') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
    </li>
  @endif

  <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Heading -->
  <div class="sidebar-heading">
    Data Master
  </div>

  <!-- Nav Item - Surat Collapse Menu -->
  @if (Route::currentRouteName() == 'surat-tugas.index')
    <li class="nav-item active">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSurat" aria-expanded="true"
        aria-controls="collapseSurat">
        <i class="fas fa-fw fa-folder"></i>
        <span>Surat</span>
      </a>
      <div id="collapseSurat" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <h6 class="collapse-header">Daftar Menu:</h6>
          <a class="collapse-item active" href="{{ route('surat-tugas.index') }}">Surat Pengganti Driver</a>
          <a class="collapse-item" href="{{ route('surat-tugas-mandiri.index') }}">Surat Tugas Mandiri</a>
          <a class="collapse-item" href="{{ route('surat-tugas-promotor.index') }}">Surat Tugas Promotor</a>
        </div>
      </div>
    </li>
  @elseif(Route::currentRouteName() == 'surat-tugas-mandiri.index')
    <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSurat" aria-expanded="true"
        aria-controls="collapseSurat">
        <i class="fas fa-fw fa-folder"></i>
        <span>Surat</span>
      </a>
      <div id="collapseSurat" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <h6 class="collapse-header">Daftar Menu:</h6>
          <a class="collapse-item" href="{{ route('surat-tugas.index') }}">Surat Pengganti Driver</a>
          <a class="collapse-item active" href="{{ route('surat-tugas-mandiri.index') }}">Surat Tugas Mandiri</a>
          <a class="collapse-item" href="{{ route('surat-tugas-promotor.index') }}">Surat Tugas Promotor</a>
        </div>
      </div>
    </li>
  @elseif(Route::currentRouteName() == 'surat-tugas-promotor.index')
    <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSurat" aria-expanded="true"
        aria-controls="collapseSurat">
        <i class="fas fa-fw fa-folder"></i>
        <span>Surat</span>
      </a>
      <div id="collapseSurat" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <h6 class="collapse-header">Daftar Menu:</h6>
          <a class="collapse-item" href="{{ route('surat-tugas.index') }}">Surat Pengganti Driver</a>
          <a class="collapse-item" href="{{ route('surat-tugas-mandiri.index') }}">Surat Tugas Mandiri</a>
          <a class="collapse-item active" href="{{ route('surat-tugas-promotor.index') }}">Surat Tugas Promotor</a>
        </div>
      </div>
    </li>
  @else
    <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSurat" aria-expanded="true"
        aria-controls="collapseSurat">
        <i class="fas fa-fw fa-folder"></i>
        <span>Surat</span>
      </a>
      <div id="collapseSurat" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <h6 class="collapse-header">Daftar Menu:</h6>
          <a class="collapse-item" href="{{ route('surat-tugas.index') }}">Surat Pengganti Driver</a>
          <a class="collapse-item" href="{{ route('surat-tugas-mandiri.index') }}">Surat Tugas Mandiri</a>
          <a class="collapse-item" href="{{ route('surat-tugas-promotor.index') }}">Surat Tugas Promotor</a>
        </div>
      </div>
    </li>
  @endif

  <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Heading -->
  <div class="sidebar-heading">
    Lainnya
  </div>

  <!-- Nav Item - Dummy -->
  @if (Route::currentRouteName() == 'blank.index')
    <li class="nav-item active">
      <a class="nav-link" href="{{ route('blank.index') }}">
        <i class="fas fa-fw fa-chart-area"></i>
        <span>Dummy</span>
      </a>
    </li>
  @else
    <li class="nav-item">
      <a class="nav-link" href="{{ route('blank.index') }}">
        <i class="fas fa-fw fa-chart-area"></i>
        <span>Dummy</span>
      </a>
    </li>
  @endif

  <!-- Nav Item - Lowongan Pekerjaan -->
  @if (Route::currentRouteName() == 'lowongan-pekerjaan.index')
      <li class="nav-item active">
          <a class="nav-link" href="{{ route('lowongan-pekerjaan.index') }}">
              <i class="fas fa-fw fa-briefcase"></i>
              <span>Lowongan Pekerjaan</span>
          </a>
      </li>
  @else
      <li class="nav-item">
          <a class="nav-link" href="{{ route('lowongan-pekerjaan.index') }}">
              <i class="fas fa-fw fa-briefcase"></i>
              <span>Lowongan Pekerjaan</span>
          </a>
      </li>
  @endif

  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>