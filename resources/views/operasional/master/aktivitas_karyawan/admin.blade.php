<div class="row mt-5">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <button class="btn btn-primary" data-toggle="modal" data-target=".modal[data-entity='aktivitasKaryawan'][data-method='tambah']">Tambah Aktivitas Karyawan</button>
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
          <table class="table table-hover" data-entity="aktivitasKaryawan">
            <thead>
              <th>#</th>
              <th>Aktivitas Karyawan</th>
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
<div class="modal fade" data-entity="aktivitasKaryawan" data-method="tambah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Tambah Aktivitas Karyawan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Aktivitas Karyawan</label>
            <input type="text" class="form-control" name="aktivitas_karyawan">
            <small class="form-text text-danger" data-role="feedback" data-field="aktivitas_karyawan"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="aktivitasKaryawan" data-method="ubah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Ubah Aktivitas Karyawan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <input type="hidden" name="id">
            <label>Aktivitas Karyawan</label>
            <input type="text" class="form-control" name="aktivitas_karyawan">
            <small class="form-text text-danger" data-role="feedback" data-field="aktivitas_karyawan"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Ubah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="{{ url('/assets/js/adapter/admin/AktivitasKaryawan.js') }}"></script>
<script type="text/javascript">
  let aktivitasKaryawan = AktivitasKaryawan();

  aktivitasKaryawan.setQuery(@json($serializedQuery));
  aktivitasKaryawan.responsiveContract();
</script>