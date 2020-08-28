<div class="row mt-5">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <button class="btn btn-primary" data-toggle="modal" data-target=".modal[data-entity='user'][data-method='tambah']">Tambah User</button>
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
          <table class="table table-hover" data-entity="user">
            <thead>
              <th>#</th>
              <th>Username</th>
              <th>Role</th>
              <th>Cabang</th>
              <th>PIC</th>
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
<div class="modal fade" data-entity="user" data-method="tambah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Tambah User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control" name="username">
            <small class="form-text text-danger" data-role="feedback" data-field="username"></small>
          </div>
          <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" name="password">
            <small class="form-text text-danger" data-role="feedback" data-field="password"></small>
          </div>
          <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password" class="form-control" name="password_verify">
            <small class="form-text text-danger" data-role="feedback" data-field="password_verify"></small>
          </div>
          <div class="form-group">
            <label>Role</label>
            <select class="form-control" name="role_id"></select>
            <small class="form-text text-danger" data-role="feedback" data-field="role_id"></small>
          </div>
          <div class="form-group">
            <label>Pilih Cabang</label>
            <select class="form-control" name="cabang_id"></select>
            <small class="form-text text-danger" data-role="feedback" data-field="cabang_id"></small>
          </div>
          <div class="form-group">
            <label>Pilih Karyawan</label>
            <select class="form-control" name="tugas_karyawan_id"></select>
            <small class="form-text text-danger" data-role="feedback" data-field="tugas_karyawan_id"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="user" data-method="ubah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Ubah User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <input type="hidden" name="id">
            <label>Username</label>
            <input type="text" class="form-control" name="username">
            <small class="form-text text-danger" data-role="feedback" data-field="username"></small>
          </div>
          <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" name="password">
            <small class="form-text text-danger" data-role="feedback" data-field="password"></small>
          </div>
          <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password" class="form-control" name="password_verify">
            <small class="form-text text-danger" data-role="feedback" data-field="password_verify"></small>
          </div>
          <div class="form-group">
            <label>Pilih Cabang</label>
            <select class="form-control" name="cabang_id"></select>
            <small class="form-text text-danger" data-role="feedback" data-field="cabang_id"></small>
          </div>
          <div class="form-group">
            <label>Pilih Karyawan</label>
            <select class="form-control" name="tugas_karyawan_id"></select>
            <small class="form-text text-danger" data-role="feedback" data-field="tugas_karyawan_id"></small>
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
<script src="{{ url('/assets/js/adapter/admin/Role.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/User.js') }}"></script>
<script type="text/javascript">
  let user = User();

  user.setQuery(@json($serializedQuery));
  user.responsiveContract();
</script>