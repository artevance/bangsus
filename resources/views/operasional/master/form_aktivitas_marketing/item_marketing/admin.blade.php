<button class="btn btn-primary" data-toggle="modal" data-target=".modal[data-entity='itemMarketing'][data-method='tambah']">Tambah Item Marketing</button>
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
  <table class="table table-hover" data-entity="itemMarketing">
    <thead>
      <th>#</th>
      <th>Item Marketing</th>
      <th>Aksi</th>
    </thead>
    <tbody data-role="dataWrapper"></tbody>
  </table>
</div>

<!-- Modal -->
<div class="modal fade" data-entity="itemMarketing" data-method="tambah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Tambah Item Marketing</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Item Marketing</label>
            <input type="text" class="form-control" name="item_marketing">
            <small class="form-text text-danger" data-role="feedback" data-field="itemMarketing"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="itemMarketing" data-method="ubah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Ubah Item Marketing</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <input type="hidden" name="id">
            <label>Item Marketing</label>
            <input type="text" class="form-control" name="item_marketing">
            <small class="form-text text-danger" data-role="feedback" data-field="itemMarketing"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Ubah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="{{ url('/assets/js/adapter/admin/ItemMarketing.js') }}"></script>
<script type="text/javascript">
  let itemMarketing = ItemMarketing();

  itemMarketing.setQuery(@json($serializedQuery));
  itemMarketing.responsiveContract();
</script>