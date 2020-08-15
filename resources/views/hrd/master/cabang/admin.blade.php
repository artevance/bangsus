<div class="row mt-5">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <button class="btn btn-primary" data-toggle="modal" data-target=".modal[data-entity='cabang'][data-method='tambah']">Tambah Cabang</button>
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
          <table class="table table-hover" data-entity="cabang">
            <thead>
              <th>#</th>
              <th>Kode Cabang</th>
              <th>Cabang</th>
              <th>Tipe Cabang</th>
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
<div class="modal fade" data-entity="cabang" data-method="tambah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Tambah Cabang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Kode Cabang</label>
            <input type="text" class="form-control" name="kode_cabang">
            <small class="form-text text-danger" data-role="feedback" data-field="kode_cabang"></small>
          </div>
          <div class="form-group">
            <label>Cabang</label>
            <input type="text" class="form-control" name="cabang">
            <small class="form-text text-danger" data-role="feedback" data-field="cabang"></small>
          </div>
          <div class="form-group">
            <label>Tipe Cabang</label>
            <select class="form-control" name="tipe_cabang_id"></select>
            <small class="form-text text-danger" data-role="feedback" data-field="tipe_cabang_id"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="cabang" data-method="ubah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Ubah Cabang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <input type="hidden" name="id">
            <label>Kode Cabang</label>
            <input type="text" class="form-control" name="kode_cabang">
            <small class="form-text text-danger" data-role="feedback" data-field="kode_cabang"></small>
          </div>
          <div class="form-group">
            <label>Cabang</label>
            <input type="text" class="form-control" name="cabang">
            <small class="form-text text-danger" data-role="feedback" data-field="cabang"></small>
          </div>
          <div class="form-group">
            <label>Tipe Cabang</label>
            <select class="form-control" name="tipe_cabang_id"></select>
            <small class="form-text text-danger" data-role="feedback" data-field="tipe_cabang_id"></small>
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
<script type="text/javascript">
  let cabang = Cabang();

  cabang.setQuery(@json($serializedQuery));
  cabang.responsiveContract();
</script>