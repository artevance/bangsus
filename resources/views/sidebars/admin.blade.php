<li class="nav-item nav-category">Main</li>
<li class="nav-item">
  <a href="{{ url('/dashboard') }}" class="nav-link">
    <i class="far fa-tachometer link-icon"></i>
    <span class="link-title">Dashboard</span>
  </a>
</li>
<li class="nav-item nav-category">HRD</li>
<li class="nav-item">
  <a class="nav-link" data-toggle="collapse" href="#masterHRD" role="button">
    <i class="far fa-ruler link-icon"></i>
    <span class="link-title">Master</span>
    <i class="link-arrow" data-feather="chevron-down"></i>
  </a>
  <div class="collapse" id="masterHRD">
    <ul class="nav sub-menu">
      <li class="nav-item">
        <a href="{{ url('/hrd/master/tipe_kontak') }}" class="nav-link">Tipe Kontak</a>
      </li> 
      <li class="nav-item">
        <a href="{{ url('/hrd/master/tipe_alamat') }}" class="nav-link">Tipe Alamat</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/hrd/master/tipe_foto_karyawan') }}" class="nav-link">Tipe Foto Karyawan</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/hrd/master/jabatan') }}" class="nav-link">Jabatan</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/hrd/master/divisi') }}" class="nav-link">Divisi</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/hrd/master/tipe_cabang') }}" class="nav-link">Tipe Cabang</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/hrd/master/cabang') }}" class="nav-link">Cabang</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/hrd/master/tipe_absensi') }}" class="nav-link">Tipe Absensi</a>
      </li>
    </ul>
  </div>
</li>
<li class="nav-item">
  <a class="nav-link" data-toggle="collapse" href="#karyawan" role="button">
    <i class="far fa-users link-icon"></i>
    <span class="link-title">Karyawan</span>
    <i class="link-arrow" data-feather="chevron-down"></i>
  </a>
  <div class="collapse" id="karyawan">
    <ul class="nav sub-menu">
      <li class="nav-item">
        <a href="{{ url('/hrd/karyawan') }}" class="nav-link">Karyawan</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/hrd/tugas_karyawan') }}" class="nav-link">Tugas Karyawan</a>
      </li>
    </ul>
  </div>
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
        <a href="{{ url('/hrd/absensi/pengajuan_jadwal_absensi') }}" class="nav-link">Pengajuan Jadwal Absensi</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/hrd/absensi/impor_jadwal') }}" class="nav-link">Impor Jadwal</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/hrd/absensi/impor_absensi') }}" class="nav-link">Impor Absensi</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/hrd/absensi/ekspor_absensi') }}" class="nav-link">Ekspor Absensi</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/hrd/absensi/laporan_jadwal') }}" class="nav-link">Laporan Jadwal</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/hrd/absensi/laporan_keterlambatan') }}" class="nav-link">Laporan Keterlambatan</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/hrd/absensi/laporan_absensi') }}" class="nav-link">Laporan Absensi</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/hrd/absensi/laporan_log_absen') }}" class="nav-link">Laporan Log Absen</a>
      </li>
    </ul>
  </div>
</li>
<li class="nav-item nav-category">Operasional</li>
<li class="nav-item">
  <a class="nav-link" data-toggle="collapse" href="#masterOperasional" role="button">
    <i class="far fa-ruler link-icon"></i>
    <span class="link-title">Master</span>
    <i class="link-arrow" data-feather="chevron-down"></i>
  </a>
  <div class="collapse" id="masterOperasional">
    <ul class="nav sub-menu">
      <li class="nav-item">
        <a href="{{ url('/operasional/master/satuan') }}" class="nav-link">Satuan</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/operasional/master/supplier') }}" class="nav-link">Supplier</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/operasional/master/item_goreng') }}" class="nav-link">Item Goreng</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/operasional/master/tipe_proses_produksi') }}" class="nav-link">Tipe Proses Produksi</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/operasional/master/quality_control') }}" class="nav-link">Quality Control</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/operasional/master/aktivitas_karyawan') }}" class="nav-link">Aktivitas Karyawan</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/operasional/master/atribut_karyawan') }}" class="nav-link">Atribut Karyawan</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/operasional/master/kegiatan_kebersihan') }}" class="nav-link">Kegiatan Kebersihan</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/operasional/master/general_cleaning') }}" class="nav-link">General Cleaning</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/operasional/master/form_foto') }}" class="nav-link">Form Foto</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/operasional/master/form_laporan_foto') }}" class="nav-link">Form Laporan Foto</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/operasional/master/form_aktivitas_marketing') }}" class="nav-link">Form Aktivitas Marketing</a>
      </li>
    </ul>
  </div>
</li>
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
  <a href="{{ url('/operasional/form_foto') }}" class="nav-link">
    <i class="far fa-image link-icon"></i>
    <span class="link-title">Form Foto</span>
  </a>
</li>
<li class="nav-item">
  <a href="{{ url('/operasional/form_laporan_foto') }}" class="nav-link">
    <i class="far fa-camera link-icon"></i>
    <span class="link-title">Form Laporan Foto</span>
  </a>
</li>
<li class="nav-item">
  <a href="{{ url('/operasional/form_denda_foto') }}" class="nav-link">
    <i class="far fa-hammer link-icon"></i>
    <span class="link-title">Form Denda Foto</span>
  </a>
</li>
<li class="nav-item">
  <a href="{{ url('/operasional/form_aktivitas_marketing') }}" class="nav-link">
    <i class="far fa-megaphone link-icon"></i>
    <span class="link-title">Form Aktivitas Marketing</span>
  </a>
</li>
<li class="nav-item">
  <a class="nav-link" data-toggle="collapse" href="#laporanOperasional" role="button">
    <i class="far fa-file link-icon"></i>
    <span class="link-title">Laporan</span>
    <i class="link-arrow" data-feather="chevron-down"></i>
  </a>
  <div class="collapse" id="laporanOperasional">
    <ul class="nav sub-menu">
      <li class="nav-item">
        <a href="{{ url('/operasional/laporan/form_c/produksi') }}" class="nav-link">Form C1</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/operasional/laporan/form_denda_foto') }}" class="nav-link">Form Denda Foto</a>
      </li>
    </ul>
  </div>
</li>
<li class="nav-item">
  <a class="nav-link" data-toggle="collapse" href="#laporanRekapOperasional" role="button">
    <i class="far fa-file-alt link-icon"></i>
    <span class="link-title">Laporan Rekap</span>
    <i class="link-arrow" data-feather="chevron-down"></i>
  </a>
  <div class="collapse" id="laporanRekapOperasional">
    <ul class="nav sub-menu">
      <li class="nav-item">
        <a href="{{ url('/operasional/laporan_rekap/form_aktivitas_marketing') }}" class="nav-link">Form Aktivitas Marketing</a>
      </li>
    </ul>
  </div>
</li>
<li class="nav-item">
  <a class="nav-link" data-toggle="collapse" href="#laporanFrekuensiOperasional" role="button">
    <i class="far fa-chart-area link-icon"></i>
    <span class="link-title">Laporan Frekuensi</span>
    <i class="link-arrow" data-feather="chevron-down"></i>
  </a>
  <div class="collapse" id="laporanFrekuensiOperasional">
    <ul class="nav sub-menu">
      <li class="nav-item">
        <a href="{{ url('/operasional/laporan_frekuensi/form_c/produksi') }}" class="nav-link">Form C1</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/operasional/laporan_frekuensi/form_c/quality_control') }}" class="nav-link">Form C2</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/operasional/laporan_frekuensi/form_c/atribut_karyawan') }}" class="nav-link">Form C3</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/operasional/laporan_frekuensi/form_c/kebersihan') }}" class="nav-link">Form C4</a>
      </li>
    </ul>
  </div>
</li>
<li class="nav-item nav-category">Autentikasi</li>
<li class="nav-item">
  <a href="{{ url('/user') }}" class="nav-link">
    <i class="far fa-user link-icon"></i>
    <span class="link-title">User</span>
  </a>
</li>