<li class="nav-item nav-category">Main</li>
<li class="nav-item">
  <a href="{{ url('/dashboard') }}" class="nav-link">
    <i class="far fa-tachometer link-icon"></i>
    <span class="link-title">Dashboard</span>
  </a>
</li>
<li class="nav-item">
  <a class="nav-link" data-toggle="collapse" href="#absensi" role="button">
    <i class="far fa-users-class link-icon"></i>
    <span class="link-title">Absensi</span>
    <i class="link-arrow" data-feather="chevron-down"></i>
  </a>
  <div class="collapse" id="absensi">
    <ul class="nav sub-menu">
      <li class="nav-item">
        <a href="{{ url('/hrd/absensi/manual') }}" class="nav-link">Manual</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/hrd/absensi/pengajuan_jadwal') }}" class="nav-link">Pengajuan Jadwal</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/hrd/absensi/impor_jadwal') }}" class="nav-link">Impor Jadwal</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/hrd/absensi/impor_absensi') }}" class="nav-link">Impor Absensi</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/hrd/absensi/laporan_jadwal') }}" class="nav-link">Laporan Jadwal</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/hrd/absensi/laporan_absensi') }}" class="nav-link">Laporan Absensi</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/hrd/absensi/laporan_log_absen') }}" class="nav-link">Laporan Log Absen</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/hrd/absensi/laporan_keterlambatan') }}" class="nav-link">Laporan Keterlambatan</a>
      </li>
    </ul>
  </div>
</li>
<li class="nav-item nav-category">Operasional</li>
<li class="nav-item">
  <a class="nav-link" data-toggle="collapse" href="#formC" role="button">
    <i class="far fa-file-invoice link-icon"></i>
    <span class="link-title">Form C</span>
    <i class="link-arrow" data-feather="chevron-down"></i>
  </a>
  <div class="collapse" id="formC">
    <ul class="nav sub-menu">
      <li class="nav-item">
        <a href="{{ url('/operasional/form_c/produksi') }}" class="nav-link">Form C1</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/operasional/form_c/quality_control') }}" class="nav-link">Form C2</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/operasional/form_c/atribut_karyawan') }}" class="nav-link">Form C3</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/operasional/form_c/kebersihan') }}" class="nav-link">Form C4</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/operasional/form_c/general_cleaning') }}" class="nav-link">Form C5</a>
      </li>
    </ul>
  </div>
</li>
<li class="nav-item">
  <a class="nav-link" data-toggle="collapse" href="#denda" role="button">
    <i class="far fa-hammer link-icon"></i>
    <span class="link-title">Denda</span>
    <i class="link-arrow" data-feather="chevron-down"></i>
  </a>
  <div class="collapse" id="denda">
    <ul class="nav sub-menu">
      <li class="nav-item">
        <a href="#" class="nav-link">Form C1</a>
      </li>
    </ul>
  </div>
</li>