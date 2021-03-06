<div class="row">
  <div class="col-12">
    <form data-role="search">
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">
              Cabang
            </span>
          </div>
          <select class="form-control" name="cabang_id">
            @foreach($cabangs as $cabang)
              <option value="{{ $cabang->id }}" @if($cabang->id == $query['cabang_id']) {{ 'selected' }} @endif>
                {{ $cabang->kode_cabang }} - {{ $cabang->cabang }}
              </option>
            @endforeach
          </select>
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">
              Tanggal Form
            </span>
          </div>
          <input type="date" class="form-control" name="tanggal_form" value="{{ $query['tanggal_form'] }}">
          <div class="input-group-prepend">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<button class="btn btn-primary mt-5" data-toggle="modal" data-target=".modal[data-entity='formMargarin'][data-method='tambah']">Tambah Form Margarin</button>
<div class="table-responsive mt-2">
  <table class="table table-hover" data-entity="formMargarin">
    <thead>
      <th>#</th>
      <th>NIP</th>
      <th>Nama Karyawan</th>
      <th>Jam</th>
      <th>Qty</th>
      <th>Satuan</th>
      <th>Tipe Proses Margarin</th>
      <th>Aksi</th>
    </thead>
    <tbody data-role="dataWrapper"></tbody>
  </table>
</div>

<!-- Modal -->
<div class="modal fade" data-entity="formMargarin" data-method="tambah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Tambah Form Margarin</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
          <div class="form-group row">
            <div class="col-12 col-lg-4">
              <label>Kode Cabang</label>
              <input type="text" class="form-control" name="kode_cabang" readonly>
              <small class="form-text text-danger" data-role="feedback" data-field="kode_cabang"></small>
            </div>
            <div class="col-12 col-lg-8">
              <label>Nama Cabang</label>
              <input type="text" class="form-control" name="cabang" readonly>
              <small class="form-text text-danger" data-role="feedback" data-field="cabang"></small>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-12 col-lg-6">
              <label>Tanggal Form</label>
              <input type="date" class="form-control" name="tanggal_form" readonly>
              <small class="form-text text-danger" data-role="feedback" data-field="tanggal_form"></small>
            </div>
            <div class="col-12 col-lg-6">
              <label>Jam</label>
              <input type="time" class="form-control" name="jam">
              <small class="form-text text-danger" data-role="feedback" data-field="jam"></small>
            </div>
          </div>
          <div class="form-group">
            <label>Karyawan</label>
            <select class="form-control" name="tugas_karyawan_id"></select>
            <small class="form-text text-danger" data-role="feedback" data-field="tugas_karyawan_id"></small>
          </div>
          <div class="form-group row">
            <div class="col-12 col-lg-3">
              <label>Tipe Proses Margarin</label>
              <select class="form-control" name="tipe_proses_margarin_id"></select>
              <small class="form-text text-danger" data-role="feedback" data-field="tipe_proses_margarin_id"></small>
            </div>
            <div class="col-12 col-lg-3">
              <label>Qty</label>
              <input type="number" class="form-control" name="qty" step="any">
              <small class="form-text text-danger" data-role="feedback" data-field="qty"></small>
            </div>
            <div class="col-12 col-lg-3">
              <label>Satuan</label>
              <select class="form-control" name="satuan_id"></select>
              <small class="form-text text-danger" data-role="feedback" data-field="satuan_id"></small>
            </div>
          </div>
          <div class="form-group">
            <label>Keterangan</label>
            <textarea class="form-control form-control-sm" name="keterangan"></textarea>
              <small class="form-text text-danger" data-role="feedback" data-field="keterangan"></small>
          </div>
          <div class="form-group">
            <label>Foto</label>
            <input type="hidden" name="gambar">
            <div data-rel="booth"></div>
            <small class="form-text text-danger" data-role="feedback" data-field="gambar"></small>
            <button type="button" class="btn btn-sm btn-secondary" data-rel="webcamBtn" data-role="capture">Ambil Foto</button>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="formMargarin" data-method="ubah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Ubah Form Margarin</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id">
          <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
          <div class="form-group row">
            <div class="col-12 col-lg-4">
              <label>Kode Cabang</label>
              <input type="text" class="form-control" name="kode_cabang" readonly>
              <small class="form-text text-danger" data-role="feedback" data-field="kode_cabang"></small>
            </div>
            <div class="col-12 col-lg-8">
              <label>Nama Cabang</label>
              <input type="text" class="form-control" name="cabang" readonly>
              <small class="form-text text-danger" data-role="feedback" data-field="cabang"></small>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-12 col-lg-6">
              <label>Tanggal Form</label>
              <input type="date" class="form-control" name="tanggal_form" readonly>
              <small class="form-text text-danger" data-role="feedback" data-field="tanggal_form"></small>
            </div>
            <div class="col-12 col-lg-6">
              <label>Jam</label>
              <input type="time" class="form-control" name="jam">
              <small class="form-text text-danger" data-role="feedback" data-field="jam"></small>
            </div>
          </div>
          <div class="form-group">
            <label>Karyawan</label>
            <select class="form-control" name="tugas_karyawan_id"></select>
            <small class="form-text text-danger" data-role="feedback" data-field="tugas_karyawan_id"></small>
          </div>
          <div class="form-group row">
            <div class="col-12 col-lg-3">
              <label>Tipe Proses Margarin</label>
              <select class="form-control" name="tipe_proses_margarin_id"></select>
              <small class="form-text text-danger" data-role="feedback" data-field="tipe_proses_margarin_id"></small>
            </div>
            <div class="col-12 col-lg-3">
              <label>Qty</label>
              <input type="number" class="form-control" name="qty" step="any">
              <small class="form-text text-danger" data-role="feedback" data-field="qty"></small>
            </div>
            <div class="col-12 col-lg-3">
              <label>Satuan</label>
              <select class="form-control" name="satuan_id"></select>
              <small class="form-text text-danger" data-role="feedback" data-field="satuan_id"></small>
            </div>
          </div>
          <div class="form-group">
            <label>Keterangan</label>
            <textarea class="form-control form-control-sm" name="keterangan"></textarea>
              <small class="form-text text-danger" data-role="feedback" data-field="keterangan"></small>
          </div>
          <div class="form-group">
            <label>Foto</label>
            <input type="hidden" name="gambar">
            <div data-rel="booth"></div>
            <small class="form-text text-danger" data-role="feedback" data-field="gambar"></small>
            <button type="button" class="btn btn-sm btn-secondary" data-rel="webcamBtn" data-role="capture">Ambil Foto</button>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Ubah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="formMargarin" data-method="hapus" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Hapus Form Margarin</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id">
          <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
          <h6 class="text-center my-3">Apakah anda yakin ingin menghapus data?</h6>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Hapus</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="{{ url('/assets/js/adapter/admin/TipeCabang.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/Cabang.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/Divisi.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/Jabatan.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/GolonganDarah.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/JenisKelamin.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/Karyawan.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/TugasKaryawan.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/Satuan.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/TipeProsesMargarin.js') }}"></script>
<script src="{{ url('/assets/js/utils/Webcam.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/FormMargarin.js') }}"></script>
<script type="text/javascript">
  let formMargarin = FormMargarin();

  formMargarin.setQuery(@json($serializedQuery));
  formMargarin.responsiveContract();
</script>