<div class="row mt-5">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <button class="btn btn-primary" data-toggle="modal" data-target=".modal[data-entity='tipeCabang'][data-method='tambah']">Tambah Tipe Cabang</button>
        <table class="table table-hover mt-5" data-entity="tipeCabang">
          <thead>
            <th>#</th>
            <th>Tipe Cabang</th>
            <th>Aksi</th>
          </thead>
          <tbody data-role="dataWrapper"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" data-entity="tipeCabang" data-method="tambah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Tambah Tipe Cabang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Tipe Cabang</label>
            <input type="text" class="form-control" name="tipe_cabang">
            <small class="form-text text-danger" data-role="feedback" data-field="tipe_cabang"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="tipeCabang" data-method="ubah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Ubah Tipe Cabang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <input type="hidden" name="id">
            <label>Tipe Cabang</label>
            <input type="text" class="form-control" name="tipe_cabang">
            <small class="form-text text-danger" data-role="feedback" data-field="tipe_cabang"></small>
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
<script type="text/javascript">
  let tipeCabang = TipeCabang();

  tipeCabang.load();
  tipeCabang.responsiveContract();
</script>