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
              Kelompok Foto
            </span>
          </div>
          <select class="form-control" name="kelompok_foto_id">
            @foreach($kelompokFotos as $kelompokFoto)
              <option value="{{ $kelompokFoto->id }}" @if($kelompokFoto->id == $query['kelompok_foto_id']) {{ 'selected' }} @endif>
                {{ $kelompokFoto->kelompok_foto }}
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
<div class="mt-5">
  <div data-role="accordion" data-entity="formDendaFoto"></div>
</div>

<!-- Modal -->
<div class="modal fade" data-entity="formDendaFoto" data-method="tambahDenda" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Tambah Form Denda Foto</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
          <input type="hidden" name="form_foto_id">
          <input type="hidden" name="denda" value="1">
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
            <label>Kelompok Foto</label>
            <input type="hidden" name="kelompok_foto_id">
            <input type="text" class="form-control" name="kelompok_foto" readonly>
            <small class="form-text text-danger" data-role="feedback" data-field="kelompok_foto_id"></small>
          </div>
          <div class="form-group">
            <label>Foto</label>
            <div data-role="gambar">
              
            </div>
          </div>
          <div class="form-group">
            <label>Denda</label>
            <div class="table-responsive">
              <table class="table" data-entity="denda">
                <thead>
                  <th>Denda</th>
                  <th>Nominal</th>
                  <th>Keterangan</th>
                  <th>Aksi</th>
                </thead>
                <tbody data-role="dataWrapper"></tbody>
              </table>
            </div>
            <button class="btn btn-sm btn-secondary mt-5" type="button" data-entity="denda" data-role="tambah" data-inc="1">
              + Tambah Denda
            </button>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="formDendaFoto" data-method="tambahTidakDenda" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Tambah Form Denda Foto</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
          <input type="hidden" name="form_foto_id">
          <input type="hidden" name="denda" value="0">
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
            <label>Kelompok Foto</label>
            <input type="hidden" name="kelompok_foto_id">
            <input type="text" class="form-control" name="kelompok_foto" readonly>
            <small class="form-text text-danger" data-role="feedback" data-field="kelompok_foto_id"></small>
          </div>
          <div class="form-group">
            <label>Foto</label>
            <div data-role="gambar">
              
            </div>
          </div>
          <h6 class="text-center my-3">Apakah anda yakin data ini bebas dari denda?</h6>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="formDendaFoto" data-method="ubahDenda" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Ubah Form Denda Foto</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" value="1">
          <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
          <input type="hidden" name="form_foto_id">
          <input type="hidden" name="denda" value="1">
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
            <label>Kelompok Foto</label>
            <input type="hidden" name="kelompok_foto_id">
            <input type="text" class="form-control" name="kelompok_foto" readonly>
            <small class="form-text text-danger" data-role="feedback" data-field="kelompok_foto_id"></small>
          </div>
          <div class="form-group">
            <label>Foto</label>
            <div data-role="gambar">
              
            </div>
          </div>
          <div class="form-group">
            <label>Denda</label>
            <div class="table-responsive">
              <table class="table" data-entity="denda">
                <thead>
                  <th>Denda</th>
                  <th>Nominal</th>
                  <th>Keterangan</th>
                  <th>Aksi</th>
                </thead>
                <tbody data-role="dataWrapper"></tbody>
              </table>
            </div>
            <button class="btn btn-sm btn-secondary mt-5" type="button" data-entity="denda" data-role="tambah" data-inc="1">
              + Tambah Denda
            </button>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Ubah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="formDendaFoto" data-method="hapus" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Hapus Form Denda Foto</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id">
          <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
          <h6 class="text-center my-3">Apakah anda yakin ingin menghapus data denda?</h6>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Hapus</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="formDendaFoto" data-method="detail" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Detail Form Denda Foto</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" value="1">
          <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
          <input type="hidden" name="form_foto_id">
          <input type="hidden" name="denda" value="1">
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
            <label>Kelompok Foto</label>
            <input type="hidden" name="kelompok_foto_id">
            <input type="text" class="form-control" name="kelompok_foto" readonly>
            <small class="form-text text-danger" data-role="feedback" data-field="kelompok_foto_id"></small>
          </div>
          <div class="form-group">
            <label>Foto</label>
            <div data-role="gambar">
              
            </div>
          </div>
          <div class="form-group">
            <label>Denda</label>
            <div class="table-responsive">
              <table class="table" data-entity="denda">
                <thead>
                  <th>Denda</th>
                  <th>Nominal</th>
                  <th>Keterangan</th>
                  <th>Aksi</th>
                </thead>
                <tbody data-role="dataWrapper"></tbody>
              </table>
            </div>
            <button class="btn btn-sm btn-secondary mt-5" type="button" data-entity="denda" data-role="tambah" data-inc="1" disabled>
              + Tambah Denda
            </button>
          </div>
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
<script src="{{ url('/assets/js/adapter/admin/DendaFoto.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/KelompokFoto.js') }}"></script>
<script src="{{ url('/assets/js/utils/Webcam.js') }}"></script>
<script src="{{ url('/assets/js/utils/Clock.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/FormFoto.js') }}"></script>
<script src="{{ url('/assets/js/adapter/admin/FormDendaFoto.js') }}"></script>
<script type="text/javascript">
  let formDendaFoto = FormDendaFoto();

  formDendaFoto.setQuery(@json($serializedQuery));
  formDendaFoto.responsiveContract();
</script>