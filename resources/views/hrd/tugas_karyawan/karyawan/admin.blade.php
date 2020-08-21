<div class="row mt-5">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <a href="{{ url()->previous() == url()->current() ? url('/hrd/karyawan') : url()->previous()}}">
          <i class="fas fa-backspace"></i> 
          Kembali
        </a>
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
        <button class="btn btn-primary" data-toggle="modal" data-target=".modal[data-entity='tugasKaryawan'][data-method='tambah']">Tambah Tugas Karyawan</button>
        <div class="table-responsive">
          <table class="table table-hover mt-5" data-entity="tugasKaryawan">
            <thead>
              <th>#</th>
              <th>Kode Cabang</th>
              <th>Nama Cabang</th>
              <th>Divisi</th>
              <th>Jabatan</th>
              <th>Tanggal Mulai</th>
              <th>Tanggal Selesai</th>
              <th>No. Finger</th>
              <th>Aksi</th>
            </thead>
            <tbody data-role="dataWrapper"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" data-entity="tugasKaryawan" data-method="tambah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Tambah Tugas Karyawan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="karyawan_id">
          <div class="form-group">
            <label>NIP</label>
            <input type="text" class="form-control" name="nip" readonly>
            <small class="form-text text-danger" data-role="feedback" data-field="nip"></small>
          </div>
          <div class="form-group">
            <label>Nama Karyawan</label>
            <input type="text" class="form-control" name="nama_karyawan" readonly>
            <small class="form-text text-danger" data-role="feedback" data-field="nama_karyawan"></small>
          </div>
          <div class="form-group">
            <label>Cabang</label>
            <select class="form-control" name="cabang_id"></select>
            <small class="form-text text-danger" data-role="feedback" data-field="cabang_id"></small>
          </div>
          <div class="form-group">
            <label>Divisi</label>
            <select class="form-control" name="divisi_id"></select>
            <small class="form-text text-danger" data-role="feedback" data-field="divisi_id"></small>
          </div>
          <div class="form-group">
            <label>Jabatan</label>
            <select class="form-control" name="jabatan_id"></select>
            <small class="form-text text-danger" data-role="feedback" data-field="jabatan_id"></small>
          </div>
          <div class="form-group">
            <label>Tanggal Mulai</label>
            <input type="date" class="form-control" name="tanggal_mulai">
            <small class="form-text text-danger" data-role="feedback" data-field="tanggal_mulai"></small>
          </div>
          <div class="form-group">
            <label>Tanggal Selesai</label>
            <input type="date" class="form-control" name="tanggal_selesai">
            <small class="form-text text-danger" data-role="feedback" data-field="tanggal_selesai"></small>
          </div>
          <div class="form-group">
            <label>No. Finger</label>
            <input type="text" class="form-control" name="no_finger">
            <small class="form-text text-danger" data-role="feedback" data-field="no_finger"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="tugasKaryawan" data-method="ubah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Ubah Tugas Karyawan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id">
          <input type="hidden" name="karyawan_id">
          <div class="form-group">
            <label>NIP</label>
            <input type="text" class="form-control" name="nip" readonly>
            <small class="form-text text-danger" data-role="feedback" data-field="nip"></small>
          </div>
          <div class="form-group">
            <label>Nama Karyawan</label>
            <input type="text" class="form-control" name="nama_karyawan" readonly>
            <small class="form-text text-danger" data-role="feedback" data-field="nama_karyawan"></small>
          </div>
          <div class="form-group">
            <label>Cabang</label>
            <select class="form-control" name="cabang_id"></select>
            <small class="form-text text-danger" data-role="feedback" data-field="cabang_id"></small>
          </div>
          <div class="form-group">
            <label>Divisi</label>
            <select class="form-control" name="divisi_id"></select>
            <small class="form-text text-danger" data-role="feedback" data-field="divisi_id"></small>
          </div>
          <div class="form-group">
            <label>Jabatan</label>
            <select class="form-control" name="jabatan_id"></select>
            <small class="form-text text-danger" data-role="feedback" data-field="jabatan_id"></small>
          </div>
          <div class="form-group">
            <label>Tanggal Mulai</label>
            <input type="date" class="form-control" name="tanggal_mulai">
            <small class="form-text text-danger" data-role="feedback" data-field="tanggal_mulai"></small>
          </div>
          <div class="form-group">
            <label>Tanggal Selesai</label>
            <input type="date" class="form-control" name="tanggal_selesai">
            <small class="form-text text-danger" data-role="feedback" data-field="tanggal_selesai"></small>
          </div>
          <div class="form-group">
            <label>No. Finger</label>
            <input type="text" class="form-control" name="no_finger">
            <small class="form-text text-danger" data-role="feedback" data-field="no_finger"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Ubah</button>
        </div>
      </form>
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
<script src="{{ url('/assets/js/adapter/admin/TugasKaryawanKaryawan.js') }}"></script>
<script type="text/javascript">
  let tugasKaryawanKaryawan = TugasKaryawanKaryawan(@json(['karyawanID' => $karyawan->id]));

  tugasKaryawanKaryawan.load();
  tugasKaryawanKaryawan.responsiveContract();
</script>