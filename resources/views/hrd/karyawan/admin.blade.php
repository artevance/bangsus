<div class="row mt-5">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <button class="btn btn-primary" data-toggle="modal" data-target=".modal[data-entity='karyawan'][data-method='tambah']">Tambah Karyawan</button>
        <div class="row mt-5">
          <div class="col-12 col-md-6">
            <form data-role="search">
              <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Cari sesuatu ..." name="q" value="{{ $query['q'] ?? '' }}">
                <div class="input-group-append">
                  <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="table-responsive mt-2">
          <table class="table table-hover" data-entity="karyawan">
            <thead>
              <th>#</th>
              <th>NIP</th>
              <th>NIK</th>
              <th>Nama Karyawan</th>
              <th>Tempat Lahir</th>
              <th>Tanggal Lahir</th>
              <th>Golongan Darah</th>
              <th>Jenis Kelamin</th>
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
<div class="modal fade" data-entity="karyawan" data-method="tambah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Tambah Karyawan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <label>Tanggal Mulai Bekerja</label>
                <input type="date" class="form-control" name="tanggal_mulai">
                <small class="form-text text-danger" data-role="feedback" data-field="tanggal_mulai"></small>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <label>Cabang Penerima</label>
                <select class="form-control" name="cabang_id"></select>
                <small class="form-text text-danger" data-role="feedback" data-field="cabang_id"></small>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <label>Divisi Saat Diterima</label>
                <select class="form-control" name="divisi_id"></select>
                <small class="form-text text-danger" data-role="feedback" data-field="divisi_id"></small>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <label>Jabatan Pertama</label>
                <select class="form-control" name="jabatan_id"></select>
                <small class="form-text text-danger" data-role="feedback" data-field="jabatan_id"></small>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <label>NIK</label>
                <input type="number" class="form-control" name="nik">
                <small class="form-text text-danger" data-role="feedback" data-field="nik"></small>
              </div>
            </div>
            <div class="col-12 col-lg-8">
              <div class="form-group">
                <label>Nama Karyawan</label>
                <input type="text" class="form-control" name="nama_karyawan">
                <small class="form-text text-danger" data-role="feedback" data-field="nama_karyawan"></small>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-lg-3">
              <div class="form-group">
                <label>Tempat Lahir</label>
                <input type="text" class="form-control" name="tempat_lahir">
                <small class="form-text text-danger" data-role="feedback" data-field="tempat_lahir"></small>
              </div>
            </div>
            <div class="col-12 col-lg-3">
              <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" class="form-control" name="tanggal_lahir">
                <small class="form-text text-danger" data-role="feedback" data-field="tanggal_lahir"></small>
              </div>
            </div>
            <div class="col-12 col-lg-3">
              <div class="form-group">
                <label>Golongan Darah</label>
                <select class="form-control" name="golongan_darah_id"></select>
                <small class="form-text text-danger" data-role="feedback" data-field="golongan_darah_id"></small>
              </div>
            </div>
            <div class="col-12 col-lg-3">
              <div class="form-group">
                <label>Jenis Kelamin</label>
                <select class="form-control" name="jenis_kelamin_id"></select>
                <small class="form-text text-danger" data-role="feedback" data-field="jenis_kelamin_id"></small>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-lg-3">
              <div class="form-group">
                <label>No. Finger</label>
                <input type="number" class="form-control" name="no_finger">
                <small class="form-text text-danger" data-role="feedback" data-field="no_finger"></small>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="karyawan" data-method="ubah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Ubah Karyawan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12 col-lg-4">
              <div class="form-group">
                <label>NIK</label>
                <input type="hidden" name="id">
                <input type="number" class="form-control" name="nik">
                <small class="form-text text-danger" data-role="feedback" data-field="nik"></small>
              </div>
            </div>
            <div class="col-12 col-lg-8">
              <div class="form-group">
                <label>Nama Karyawan</label>
                <input type="text" class="form-control" name="nama_karyawan">
                <small class="form-text text-danger" data-role="feedback" data-field="nama_karyawan"></small>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-lg-3">
              <div class="form-group">
                <label>Tempat Lahir</label>
                <input type="text" class="form-control" name="tempat_lahir">
                <small class="form-text text-danger" data-role="feedback" data-field="tempat_lahir"></small>
              </div>
            </div>
            <div class="col-12 col-lg-3">
              <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" class="form-control" name="tanggal_lahir">
                <small class="form-text text-danger" data-role="feedback" data-field="tanggal_lahir"></small>
              </div>
            </div>
            <div class="col-12 col-lg-3">
              <div class="form-group">
                <label>Golongan Darah</label>
                <select class="form-control" name="golongan_darah_id"></select>
                <small class="form-text text-danger" data-role="feedback" data-field="golongan_darah_id"></small>
              </div>
            </div>
            <div class="col-12 col-lg-3">
              <div class="form-group">
                <label>Jenis Kelamin</label>
                <select class="form-control" name="jenis_kelamin_id"></select>
                <small class="form-text text-danger" data-role="feedback" data-field="jenis_kelamin_id"></small>
              </div>
            </div>
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
<script type="text/javascript">
  let karyawan = Karyawan();

  karyawan.setQuery(@json($serializedQuery));
  karyawan.responsiveContract();
</script>