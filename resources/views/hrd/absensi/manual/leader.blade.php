<div class="row mt-5">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <form data-role="search">
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                      Cabang
                    </span>
                  </div>
                  <input type="hidden" name="cabang_id" value="{{ $query['cabang_id'] }}">
                  <select class="form-control" disabled>
                    @foreach($cabangs as $cabang)
                      <option value="{{ $cabang->id }}" @if($cabang->id == $query['cabang_id']) {{ 'selected' }} @endif>
                        {{ $cabang->kode_cabang }} - {{ $cabang->cabang }}
                      </option>
                    @endforeach
                  </select>
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                      Tipe Absensi
                    </span>
                  </div>
                  <select class="form-control" name="tipe_absensi_id">
                    @foreach($tipeAbsensis as $tipeAbsensi)
                      <option value="{{ $tipeAbsensi->id }}" @if($tipeAbsensi->id == $query['tipe_absensi_id']) {{ 'selected' }} @endif>
                        {{ $tipeAbsensi->tipe_absensi }}
                      </option>
                    @endforeach
                  </select>
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                      Tanggal Absensi
                    </span>
                  </div>
                  <input type="date" class="form-control" name="tanggal_absensi" value="{{ $query['tanggal_absensi'] }}">
                  <div class="input-group-prepend">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="table-responsive mt-2">
          <table class="table table-hover" data-entity="absensiManual">
            <thead>
              <th>#</th>
              <th>NIP</th>
              <th>Nama Karyawan</th>
              <th>Jam Jadwal</th>
              <th>Jam Absen</th>
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
<div class="modal fade" data-entity="absensiManual" data-method="tambah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Tambah Absensi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="tugas_karyawan_id">
          <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
          <input type="hidden" name="tipe_absensi_id">
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
          <div class="form-group row">
            <div class="col-12 col-lg-4">
              <label>Kode Cabang</label>
              <input type="text" class="form-control" name="kode_cabang" readonly>
              <small class="form-text text-danger" data-role="feedback" data-field="kode_cabang"></small>
            </div>
            <div class="col-12 col-lg-8">
              <label>Nama Cabang</label>
              <input type="text" class="form-control" name="cabang" readonly>
              <small class="form-text text-danger" data-role="feedback" data-field="cabang"></small>
            </div>
          </div>
          <div class="form-group">
            <label>Tanggal Absensi</label>
            <input type="date" class="form-control" name="tanggal_absensi" readonly>
            <small class="form-text text-danger" data-role="feedback" data-field="tanggal_absensi"></small>
          </div>
          <div class="form-group">
            <label>Tipe Absensi</label>
            <input text="date" class="form-control" name="tipe_absensi" readonly>
            <small class="form-text text-danger" data-role="feedback" data-field="tipe_absensi"></small>
          </div>
          <div class="form-group">
            <label>Jam Jadwal</label>
            <input type="time" class="form-control" name="jam_jadwal">
            <small class="form-text text-danger" data-role="feedback" data-field="jam_jadwal"></small>
          </div>
          <div class="form-group">
            <label>Jam Absen</label>
            <input type="time" class="form-control" name="jam_absen">
            <small class="form-text text-danger" data-role="feedback" data-field="jam_absen"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="absensiManual" data-method="ubah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Ubah Absensi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id">
          <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
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
          <div class="form-group row">
            <div class="col-12 col-lg-4">
              <label>Kode Cabang</label>
              <input type="text" class="form-control" name="kode_cabang" readonly>
              <small class="form-text text-danger" data-role="feedback" data-field="kode_cabang"></small>
            </div>
            <div class="col-12 col-lg-8">
              <label>Nama Cabang</label>
              <input type="text" class="form-control" name="cabang" readonly>
              <small class="form-text text-danger" data-role="feedback" data-field="cabang"></small>
            </div>
          </div>
          <div class="form-group">
            <label>Tanggal Absensi</label>
            <input type="date" class="form-control" name="tanggal_absensi" readonly>
            <small class="form-text text-danger" data-role="feedback" data-field="tanggal_absensi"></small>
          </div>
          <div class="form-group">
            <label>Jam Jadwal</label>
            <input type="time" class="form-control" name="jam_jadwal">
            <small class="form-text text-danger" data-role="feedback" data-field="jam_jadwal"></small>
          </div>
          <div class="form-group">
            <label>Jam Absen</label>
            <input type="time" class="form-control" name="jam_absen">
            <small class="form-text text-danger" data-role="feedback" data-field="jam_absen"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Ubah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="absensiManual" data-method="hapus" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Hapus Absensi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id">
          <h6 class="text-center my-3">Apakah anda yakin ingin menghapus data?</h6>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Hapus</button>
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
<script src="{{ url('/assets/js/adapter/admin/TipeAbsensi.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/Absensi.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/AbsensiManual.js') }}"></script>
<script type="text/javascript">
  let absensiManual = AbsensiManual();

  absensiManual.setQuery(@json($serializedQuery));
  absensiManual.responsiveContract();
</script>