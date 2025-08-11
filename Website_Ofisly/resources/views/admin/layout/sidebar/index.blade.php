<div id="layoutSidenav_nav">
  <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
      <div class="nav">
        <div class="sb-sidenav-menu-heading">Main Menu</div>
        <a class="nav-link" href="{{ route('dashboard.index') }}">
          <div class="sb-nav-link-icon"><span class="material-symbols-outlined">dashboard</span></div>
          Dashboard
        </a>
        <div class="sb-sidenav-menu-heading">Data Master</div>
        <a class="nav-link" href="{{ route('surat-tugas.index') }}">
          <div class="sb-nav-link-icon"><span class="material-symbols-outlined">mail</span></div>
          Surat Tugas
        </a>
        <a class="nav-link" href="{{ route('cuti-karyawan.index') }}">
          <div class="sb-nav-link-icon"><span class="material-symbols-outlined">beach_access</span></div>
          Cuti Karyawan
        </a>
      </div>
    </div>
    <div class="sb-sidenav-footer">
      <div class="small">Logged in as:</div>
      {{ Auth::user()->name }}
    </div>
  </nav>
</div>
