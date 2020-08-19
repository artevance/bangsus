
<a href="{{ url()->previous() == url()->current() ? url('/operasional/master/form_foto') : url()->previous()}}">
  <i class="fas fa-backspace"></i> 
  Kembali
</a>
<div class="row mt-4">
  <div class="col-12">
    <form data-role="search">
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">
              Kelompok Foto
            </span>
          </div>
          <select class="form-control" name="kelompok_foto_id">
            @foreach($kelompokFotos as $kelompokFotoData)
              <option value="{{ $kelompokFotoData->id }}" @if($kelompokFotoData->id == $kelompokFoto->id) selected @endif>
                {{ $kelompokFotoData->kelompok_foto }}
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
<button class="btn btn-primary mt-3" data-toggle="modal" data-target=".modal[data-entity='dendaFoto'][data-method='tambah']">Tambah Denda Foto</button>
<div class="table-responsive mt-3">
  <table class="table table-hover" data-entity="dendaFoto">
    <thead>
      <th>#</th>
      <th>Denda Foto</th>
      <th>Nominal</th>
      <th>Aksi</th>
    </thead>
    <tbody data-role="dataWrapper"></tbody>
  </table>
</div>

<!-- Modal -->
<div class="modal fade" data-entity="dendaFoto" data-method="tambah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Tambah Denda Foto</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Kelompok Foto</label>
            <input type="hidden" name="kelompok_foto_id">
            <input type="text" class="form-control" name="kelompok_foto" readonly>
            <small class="form-text text-danger" data-role="feedback" data-field="kelompok_foto"></small>
          </div>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Denda Foto</label>
            <input type="text" class="form-control" name="denda_foto">
            <small class="form-text text-danger" data-role="feedback" data-field="denda_foto"></small>
          </div>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nominal</label>
            <input type="number" class="form-control" name="nominal">
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
<div class="modal fade" data-entity="dendaFoto" data-method="ubah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Ubah Denda Foto</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Kelompok Foto</label>
            <input type="hidden" name="kelompok_foto_id">
            <input type="text" class="form-control" name="kelompok_foto" readonly>
            <small class="form-text text-danger" data-role="feedback" data-field="kelompok_foto"></small>
          </div>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <input type="hidden" name="id">
            <label>Denda Foto</label>
            <input type="text" class="form-control" name="denda_foto">
            <small class="form-text text-danger" data-role="feedback" data-field="denda_foto"></small>
          </div>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nominal</label>
            <input type="number" class="form-control" name="nominal">
            <small class="form-text text-danger" data-role="feedback" data-field="nominal"></small>
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
<script src="{{ url('/assets/js/adapter/admin/DendaFoto.js') }}"></script>
<script type="text/javascript">
  let dendaFoto = DendaFoto(@json(['kelompokFotoID' => $kelompokFoto->id]));

  dendaFoto.setQuery(@json($serializedQuery));
  dendaFoto.responsiveContract();
</script>