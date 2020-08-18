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
          <select class="form-control" name="cabang_id" disabled>
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
          <input type="date" class="form-control" name="tanggal_form" value="{{ $query['tanggal_form'] }}" disabled>
          <div class="input-group-prepend">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<button class="btn btn-primary mt-5" data-toggle="modal" data-target=".modal[data-entity='formAtributKaryawan'][data-method='tambah']">Tambah Form C3</button>
<div class="table-responsive mt-2">
  <table class="table table-hover" data-entity="formAtributKaryawan">
    <thead>
      <th>#</th>
      <th>NIP</th>
      <th>Nama Karyawan</th>
      <th>Jam</th>
      <th>Aksi</th>
    </thead>
    <tbody data-role="dataWrapper"></tbody>
  </table>
</div>

<!-- Modal -->
<div class="modal fade" data-entity="formAtributKaryawan" data-method="tambah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Tambah Form C3</h5>
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
              <input type="time" class="form-control" name="jam" readonly>
              <small class="form-text text-danger" data-role="feedback" data-field="jam"></small>
            </div>
          </div>
          <div class="form-group">
            <label>Karyawan</label>
            <select class="form-control" name="tugas_karyawan_id"></select>
            <small class="form-text text-danger" data-role="feedback" data-field="tugas_karyawan_id"></small>
          </div>
          <div class="form-group">
            <label>Aktivitas</label>
            <select class="form-control" name="aktivitas_karyawan_id"></select>
            <small class="form-text text-danger" data-role="feedback" data-field="aktivitas_karyawan_id"></small>
          </div>
          <div class="form-group">
            <label>Keterangan</label>
            <textarea class="form-control" name="keterangan"></textarea>
            <small class="form-text text-danger" data-role="feedback" data-field="keterangan"></small>
          </div>
          <div class="form-group">
            <label>Atribut Karyawan</label>
            @foreach($atributKaryawans as $atributKaryawan)
              <div class="form-group col">
                <label>{{ $atributKaryawan->atribut_karyawan }}</label>
                @foreach($atributKaryawan->parameter_atribut_karyawan as $parameterAtributKaryawan)
                  <div class="form-check">
                    <div class="form-check">
                      <input class="form-check-input m-0" type="radio" name="parameter_atribut_karyawan_id[{{ $atributKaryawan->id }}]" value="{{ $parameterAtributKaryawan->id }}" required>
                      <label class="form-check-label">
                        {{ $parameterAtributKaryawan->parameter_atribut_karyawan }}
                      </label>
                    </div>
                  </div>
                @endforeach
              </div>
            @endforeach
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="formAtributKaryawan" data-method="ubah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Ubah Form C2</h5>
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
              <input type="time" class="form-control" name="jam" readonly>
              <small class="form-text text-danger" data-role="feedback" data-field="jam"></small>
            </div>
          </div>
          <div class="form-group">
            <label>Karyawan</label>
            <select class="form-control" name="tugas_karyawan_id"></select>
            <small class="form-text text-danger" data-role="feedback" data-field="tugas_karyawan_id"></small>
          </div>
          <div class="form-group">
            <label>Aktivitas</label>
            <select class="form-control" name="aktivitas_karyawan_id"></select>
            <small class="form-text text-danger" data-role="feedback" data-field="aktivitas_karyawan_id"></small>
          </div>
          <div class="form-group">
            <label>Keterangan</label>
            <textarea class="form-control" name="keterangan"></textarea>
            <small class="form-text text-danger" data-role="feedback" data-field="keterangan"></small>
          </div>
          <div class="form-group">
            <label>Atribut Karyawan</label>
            @foreach($atributKaryawans as $atributKaryawan)
              <div class="form-group col">
                <label>{{ $atributKaryawan->atribut_karyawan }}</label>
                <input type="hidden" name="form_atribut_karyawan_d_id[{{ $atributKaryawan->id }}]">
                @foreach($atributKaryawan->parameter_atribut_karyawan as $parameterAtributKaryawan)
                  <div class="form-check">
                    <div class="form-check">
                      <input class="form-check-input m-0" type="radio" name="parameter_atribut_karyawan_id[{{ $atributKaryawan->id }}]" value="{{ $parameterAtributKaryawan->id }}" required>
                      <label class="form-check-label">
                        {{ $parameterAtributKaryawan->parameter_atribut_karyawan }}
                      </label>
                    </div>
                  </div>
                @endforeach
              </div>
            @endforeach
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Hapus</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="formAtributKaryawan" data-method="hapus" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Hapus Form C2</h5>
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

<script src="{{ url('/assets/js/adapter/leader/TipeCabang.js') }}"></script>
<script src="{{ url('/assets/js/adapter/leader/Cabang.js') }}"></script>
<script src="{{ url('/assets/js/adapter/leader/Divisi.js') }}"></script>
<script src="{{ url('/assets/js/adapter/leader/Jabatan.js') }}"></script>
<script src="{{ url('/assets/js/adapter/leader/GolonganDarah.js') }}"></script>
<script src="{{ url('/assets/js/adapter/leader/JenisKelamin.js') }}"></script>
<script src="{{ url('/assets/js/adapter/leader/Karyawan.js') }}"></script>
<script src="{{ url('/assets/js/adapter/leader/TugasKaryawan.js') }}"></script>
<script src="{{ url('/assets/js/adapter/leader/AktivitasKaryawan.js') }}"></script>
<script src="{{ url('/assets/js/adapter/leader/AtributKaryawan.js') }}"></script>
<script src="{{ url('/assets/js/adapter/leader/ParameterAtributKaryawan.js') }}"></script>
<script src="{{ url('/assets/js/utils/Clock.js') }}"></script>
<script src="{{ url('/assets/js/adapter/leader/FormAtributKaryawan.js') }}"></script>
<script type="text/javascript">
  let formAtributKaryawan = FormAtributKaryawan();

  formAtributKaryawan.setQuery(@json($serializedQuery));
  formAtributKaryawan.responsiveContract();
</script>