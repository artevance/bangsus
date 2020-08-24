<button class="btn btn-primary" data-toggle="modal" data-target=".modal[data-entity='kelompokFoto'][data-method='tambah']">Tambah Kelompok Foto</button>
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
  <table class="table table-hover" data-entity="kelompokFoto">
    <thead>
      <th>#</th>
      <th>Kelompok Foto</th>
      <th>Denda Bila Tidak Mengirim</th>
      <th>Form Terkirim Minimal Per Hari</th>
      <th>Jumlah Denda Foto</th>
      <th>Aksi</th>
    </thead>
    <tbody data-role="dataWrapper"></tbody>
  </table>
</div>

<!-- Modal -->
<div class="modal fade" data-entity="kelompokFoto" data-method="tambah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Tambah Kelompok Foto</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Kelompok Foto</label>
            <input type="text" class="form-control" name="kelompok_foto">
            <small class="form-text text-danger" data-role="feedback" data-field="kelompok_foto"></small>
          </div>
          <div class="form-group">
            <div class="form-check">
              <label class="form-check-label" for="defaultCheck1">
                <input class="form-check-input" type="checkbox" name="denda_tidak_kirim">
                Denda bila tidak kirim per hari
              </label>
            </div>
          </div>
          <div class="form-group">
            <label>Qty Kirim Minimal</label>
            <input type="number" class="form-control" name="qty_minimum_form" disabled>
            <small class="form-text text-danger" data-role="feedback" data-field="qty_minimum_form"></small>
          </div>
          <div class="form-group">
            <label>Nominal Denda Tidak Kirim</label>
            <input type="number" class="form-control" name="nominal" disabled>
            <small class="form-text text-danger" data-role="feedback" data-field="nominal"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="kelompokFoto" data-method="ubah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Ubah Kelompok Foto</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <input type="hidden" name="id">
            <label>Kelompok Foto</label>
            <input type="text" class="form-control" name="kelompok_foto">
            <small class="form-text text-danger" data-role="feedback" data-field="kelompok_foto"></small>
          </div>
          <div class="form-group">
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="denda_tidak_kirim" disabled>
                Denda bila tidak kirim per hari
              </label>
            </div>
          </div>
          <div class="form-group">
            <label>Qty Kirim Minimal</label>
            <input type="number" class="form-control" name="qty_minimum_form" disabled>
            <small class="form-text text-danger" data-role="feedback" data-field="qty_minimum_form"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Ubah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="{{ url('/assets/js/adapter/admin/KelompokFoto.js') }}"></script>
<script type="text/javascript">
  let kelompokFoto = KelompokFoto();

  kelompokFoto.setQuery(@json($serializedQuery));
  kelompokFoto.responsiveContract();
</script>