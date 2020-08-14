<div class="row mt-5">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <button class="btn btn-primary" data-toggle="modal" data-target=".modal[data-entity='tipeKontak'][data-method='tambah']">Tambah Tipe Kontak</button>
        <table class="table table-hover mt-5" data-entity="tipeKontak">
          <thead>
            <th>#</th>
            <th>Tipe Kontak</th>
            <th>Aksi</th>
          </thead>
          <tbody data-role="dataWrapper"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" data-entity="tipeKontak" data-method="tambah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Tambah Tipe Kontak</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Tipe Kontak</label>
            <input type="text" class="form-control" name="tipe_kontak">
            <small class="form-text text-danger" data-role="feedback" data-field="tipe_kontak"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="tipeKontak" data-method="ubah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Ubah Tipe Kontak</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <input type="hidden" name="id">
            <label>Tipe Kontak</label>
            <input type="text" class="form-control" name="tipe_kontak">
            <small class="form-text text-danger" data-role="feedback" data-field="tipe_kontak"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Ubah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="{{ url('/assets/js/adapter/admin/TipeKontak.js') }}"></script>
<script type="text/javascript">
  let tipeKontak = TipeKontak();

  tipeKontak.load();
  tipeKontak.responsiveContract();
</script>