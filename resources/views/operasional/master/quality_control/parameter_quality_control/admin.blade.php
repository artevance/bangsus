<div class="row">
  <div class="col-12">
    <form data-role="search">
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">
              Quality Control
            </span>
          </div>
          <select class="form-control">
            @foreach($qualityControls as $qualityControlData)
              <option value="{{ $qualityControlData->id }}" @if($qualityControlData->id == $qualityControl->id) selected @endif>
                {{ $qualityControlData->quality_control }}
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
<button class="btn btn-primary mt-3" data-toggle="modal" data-target=".modal[data-entity='parameterQualityControl'][data-method='tambah']">Tambah Parameter Quality Control</button>
<div class="table-responsive mt-3">
  <table class="table table-hover" data-entity="parameterQualityControl">
    <thead>
      <th>#</th>
      <th>Parameter Quality Control</th>
      <th>Jumlah Opsi</th>
      <th>Aksi</th>
    </thead>
    <tbody data-role="dataWrapper"></tbody>
  </table>
</div>

<!-- Modal -->
<div class="modal fade" data-entity="parameterQualityControl" data-method="tambah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Tambah Parameter Quality Control</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Quality Control</label>
            <input type="hidden" name="quality_control_id">
            <input type="text" class="form-control" name="quality_control" readonly>
            <small class="form-text text-danger" data-role="feedback" data-field="quality_control"></small>
          </div>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Parameter Quality Control</label>
            <input type="text" class="form-control" name="parameter_quality_control">
            <small class="form-text text-danger" data-role="feedback" data-field="parameter_quality_control"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="parameterQualityControl" data-method="ubah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Ubah Parameter Quality Control</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Quality Control</label>
            <input type="hidden" name="quality_control_id">
            <input type="text" class="form-control" name="quality_control" readonly>
            <small class="form-text text-danger" data-role="feedback" data-field="quality_control"></small>
          </div>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <input type="hidden" name="id">
            <label>Parameter Quality Control</label>
            <input type="text" class="form-control" name="parameter_quality_control">
            <small class="form-text text-danger" data-role="feedback" data-field="parameter_quality_control"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Ubah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="{{ url('/assets/js/adapter/admin/QualityControl.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/ParameterQualityControl.js') }}"></script>
<script type="text/javascript">
  let parameterQualityControl = ParameterQualityControl(@json(['qualityControlID' => $qualityControl->id]));

  parameterQualityControl.setQuery(@json($serializedQuery));
  parameterQualityControl.responsiveContract();
</script>