<div class="row">
  <div class="col-12">
    <form data-role="search">
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">
              Atribut Karyawan
            </span>
          </div>
          <select class="form-control">
            @foreach($atributKaryawans as $atributKaryawanData)
              <option value="{{ $atributKaryawanData->id }}" @if($atributKaryawanData->id == $atributKaryawan->id) selected @endif>
                {{ $atributKaryawanData->atribut_karyawan }}
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
<button class="btn btn-primary mt-3" data-toggle="modal" data-target=".modal[data-entity='parameterAtributKaryawan'][data-method='tambah']">Tambah Parameter Atribut Karyawan</button>
<div class="table-responsive mt-3">
  <table class="table table-hover" data-entity="parameterAtributKaryawan">
    <thead>
      <th>#</th>
      <th>Parameter Atribut Karyawan</th>
      <th>Aksi</th>
    </thead>
    <tbody data-role="dataWrapper"></tbody>
  </table>
</div>

<!-- Modal -->
<div class="modal fade" data-entity="parameterAtributKaryawan" data-method="tambah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Tambah Parameter Atribut Karyawan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Atribut Karyawan</label>
            <input type="hidden" name="atribut_karyawan_id">
            <input type="text" class="form-control" name="atribut_karyawan" readonly>
            <small class="form-text text-danger" data-role="feedback" data-field="atribut_karyawan"></small>
          </div>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Parameter Atribut Karyawan</label>
            <input type="text" class="form-control" name="parameter_atribut_karyawan">
            <small class="form-text text-danger" data-role="feedback" data-field="parameter_atribut_karyawan"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="parameterAtributKaryawan" data-method="ubah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Ubah Parameter Atribut Karyawan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Atribut Karyawan</label>
            <input type="hidden" name="atribut_karyawan_id">
            <input type="text" class="form-control" name="atribut_karyawan" readonly>
            <small class="form-text text-danger" data-role="feedback" data-field="atribut_karyawan"></small>
          </div>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <input type="hidden" name="id">
            <label>Parameter Atribut Karyawan</label>
            <input type="text" class="form-control" name="parameter_atribut_karyawan">
            <small class="form-text text-danger" data-role="feedback" data-field="parameter_atribut_karyawan"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Ubah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="{{ url('/assets/js/adapter/admin/AtributKaryawan.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/ParameterAtributKaryawan.js') }}"></script>
<script type="text/javascript">
  let parameterAtributKaryawan = ParameterAtributKaryawan(@json(['atributKaryawanID' => $atributKaryawan->id]));

  parameterAtributKaryawan.setQuery(@json($serializedQuery));
  parameterAtributKaryawan.responsiveContract();
</script>