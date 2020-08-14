<div class="row mt-5">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <a href="{{ url()->previous() }}"><i class="fas fa-backspace"></i> Kembali</a>
        <div class="card-title mt-5">Informasi Pribadi</div>
        <div class="row">
          <div class="col-12 col-lg-6">
            <div class="form-group">
              <label>NIP</label>
              <input type="text" class="form-control" name="nip" value="{{ $karyawan->nip }}" readonly>
            </div>
            <div class="form-group">
              <label>NIK</label>
              <input type="text" class="form-control" name="nik" value="{{ $karyawan->nik }}" readonly>
            </div>
            <div class="form-group">
              <label>Nama Karyawan</label>
              <input type="text" class="form-control" name="nama_karyawan" value="{{ $karyawan->nama_karyawan }}" readonly>
            </div>
          </div>
          <div class="col-12 col-lg-6">
            <div class="form-group">
              <label>Tempat Lahir</label>
              <input type="text" class="form-control" name="tempat_lahir" value="{{ $karyawan->tempat_lahir }}" readonly>
            </div>
            <div class="form-group">
              <label>Tanggal Lahir</label>
              <input type="date" class="form-control" name="tanggal_lahir" value="{{ $karyawan->tanggal_lahir }}" readonly>
            </div>
            <div class="row">
              <div class="col-6">
                <label>Golongan Darah</label>
                <input type="text" class="form-control" name="golongan_darah" value="{{ $karyawan->golongan_darah->golongan_darah }}" readonly>
              </div>
              <div class="col-6">
                <label>Jenis Kelamin</label>
                <input type="text" class="form-control" name="jenis_kelamin" value="{{ $karyawan->jenis_kelamin->jenis_kelamin }}" readonly>
              </div>
            </div>
          </div>
        </div>
        <div class="card-title mt-5">Penugasan Karyawan</div>
        <button class="btn btn-primary" data-toggle="modal" data-target=".modal[data-entity='karyawan'][data-method='tambah']">Tambah Tugas Karyawan</button>
        <table class="table table-hover mt-5" data-entity="tugasKaryawan">
          <thead>
            <th>#</th>
            <th>Kode Cabang</th>
            <th>Nama Cabang</th>
            <th>Divisi</th>
            <th>Jabatan</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Selesai</th>
            <th>Aksi</th>
          </thead>
          <tbody data-role="dataWrapper"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>



<script src="{{ url('/assets/js/adapter/admin/TipeCabang.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/Cabang.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/Divisi.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/Jabatan.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/GolonganDarah.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/JenisKelamin.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/Karyawan.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/TugasKaryawan.js') }}"></script>
<script type="text/javascript">
  let tugasKaryawan = TugasKaryawan(@json(['karyawanID' => $karyawan->id]));

  tugasKaryawan.load();
  tugasKaryawan.responsiveContract();
</script>