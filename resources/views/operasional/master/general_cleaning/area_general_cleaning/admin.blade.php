<button class="btn btn-primary" data-toggle="modal" data-target=".modal[data-entity='areaGeneralCleaning'][data-method='tambah']">Tambah Area General Cleaning</button>
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
  <table class="table table-hover" data-entity="areaGeneralCleaning">
    <thead>
      <th>#</th>
      <th>Area General Cleaning</th>
      <th>Jumlah Kegiatan</th>
      <th>Aksi</th>
    </thead>
    <tbody data-role="dataWrapper"></tbody>
  </table>
</div>

<!-- Modal -->
<div class="modal fade" data-entity="areaGeneralCleaning" data-method="tambah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Tambah Area General Cleaning</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Area General Cleaning</label>
            <input type="text" class="form-control" name="area_general_cleaning">
            <small class="form-text text-danger" data-role="feedback" data-field="area_general_cleaning"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="areaGeneralCleaning" data-method="ubah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Ubah Area General Cleaning</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <input type="hidden" name="id">
            <label>Area General Cleaning</label>
            <input type="text" class="form-control" name="area_general_cleaning">
            <small class="form-text text-danger" data-role="feedback" data-field="area_general_cleaning"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Ubah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="{{ url('/assets/js/adapter/admin/AreaGeneralCleaning.js') }}"></script>
<script type="text/javascript">
  let areaGeneralCleaning = AreaGeneralCleaning();

  areaGeneralCleaning.setQuery(@json($serializedQuery));
  areaGeneralCleaning.responsiveContract();
</script>