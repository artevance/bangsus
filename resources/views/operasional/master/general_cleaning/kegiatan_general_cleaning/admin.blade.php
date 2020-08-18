<div class="row">
  <div class="col-12">
    <form data-role="search">
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">
              Area General Cleaning
            </span>
          </div>
          <select class="form-control">
            @foreach($areaGeneralCleanings as $areaGeneralCleaningData)
              <option value="{{ $areaGeneralCleaningData->id }}" @if($areaGeneralCleaningData->id == $areaGeneralCleaning->id) selected @endif>
                {{ $areaGeneralCleaningData->area_general_cleaning }}
              </option>
            @endforeach
          </select>
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">
              Cari Sesuatu
            </span>
          </div>
          <input type="text" class="form-control" placeholder="Cari sesuatu ..." name="q" value="{{ $query['q'] ?? '' }}">
          <div class="input-group-append">
            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<button class="btn btn-primary mt-3" data-toggle="modal" data-target=".modal[data-entity='kegiatanGeneralCleaning'][data-method='tambah']">Tambah Kegiatan General Cleaning</button>
<div class="table-responsive mt-3">
  <table class="table table-hover" data-entity="kegiatanGeneralCleaning">
    <thead>
      <th>#</th>
      <th>Kegiatan General Cleaning</th>
      <th>Aksi</th>
    </thead>
    <tbody data-role="dataWrapper"></tbody>
  </table>
</div>

<!-- Modal -->
<div class="modal fade" data-entity="kegiatanGeneralCleaning" data-method="tambah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Tambah Kegiatan General Cleaning</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Area General Cleaning</label>
            <input type="hidden" name="area_general_cleaning_id">
            <input type="text" class="form-control" name="area_general_cleaning" readonly>
            <small class="form-text text-danger" data-role="feedback" data-field="area_general_cleaning"></small>
          </div>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Kegiatan General Cleaning</label>
            <input type="text" class="form-control" name="kegiatan_general_cleaning">
            <small class="form-text text-danger" data-role="feedback" data-field="kegiatan_general_cleaning"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="kegiatanGeneralCleaning" data-method="ubah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Ubah Kegiatan General Cleaning</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Area General Cleaning</label>
            <input type="hidden" name="area_general_cleaning_id">
            <input type="text" class="form-control" name="area_general_cleaning" readonly>
            <small class="form-text text-danger" data-role="feedback" data-field="area_general_cleaning"></small>
          </div>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <input type="hidden" name="id">
            <label>Kegiatan General Cleaning</label>
            <input type="text" class="form-control" name="kegiatan_general_cleaning">
            <small class="form-text text-danger" data-role="feedback" data-field="kegiatan_general_cleaning"></small>
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
<script src="{{ url('/assets/js/adapter/admin/KegiatanGeneralCleaning.js') }}"></script>
<script type="text/javascript">
  let kegiatanGeneralCleaning = KegiatanGeneralCleaning(@json(['areaGeneralCleaningID' => $areaGeneralCleaning->id]));

  kegiatanGeneralCleaning.setQuery(@json($serializedQuery));
  kegiatanGeneralCleaning.responsiveContract();
</script>