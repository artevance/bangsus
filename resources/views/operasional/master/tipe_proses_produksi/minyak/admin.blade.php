<button class="btn btn-primary" data-toggle="modal" data-target=".modal[data-entity='tipeProsesMinyak'][data-method='tambah']">Tambah Tipe Proses Minyak</button>
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
  <table class="table table-hover" data-entity="tipeProsesMinyak">
    <thead>
      <th>#</th>
      <th>Tipe Proses Minyak</th>
      <th>Aksi</th>
    </thead>
    <tbody data-role="dataWrapper"></tbody>
  </table>
</div>

<!-- Modal -->
<div class="modal fade" data-entity="tipeProsesMinyak" data-method="tambah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Tambah Tipe Proses Minyak</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Tipe Proses Minyak</label>
            <input type="text" class="form-control" name="tipe_proses_minyak">
            <small class="form-text text-danger" data-role="feedback" data-field="tipe_proses_minyak"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="tipeProsesMinyak" data-method="ubah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Ubah Tipe Proses Minyak</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <input type="hidden" name="id">
            <label>Tipe Proses Minyak</label>
            <input type="text" class="form-control" name="tipe_proses_minyak">
            <small class="form-text text-danger" data-role="feedback" data-field="tipe_proses_minyak"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Ubah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="{{ url('/assets/js/adapter/admin/TipeProsesMinyak.js') }}"></script>
<script type="text/javascript">
  let tipeProsesMinyak = TipeProsesMinyak();

  tipeProsesMinyak.setQuery(@json($serializedQuery));
  tipeProsesMinyak.responsiveContract();
</script>