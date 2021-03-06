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
        </div>
      </div>
    </form>
  </div>
</div>
{{-- <button class="btn btn-primary mt-5" data-toggle="modal" data-target=".modal[data-entity='formGeneralCleaning'][data-method='tambah']">Tambah Form C5</button> --}}
<div class="table-responsive mt-2">
  <table class="table table-hover" data-entity="formGeneralCleaning">
    <thead>
      <th>#</th>
      <th>General Cleaning</th>
      <th>NIP</th>
      <th>Nama Karyawan</th>
      <th>Jam</th>
      <th>Skor</th>
      <th>Aksi</th>
    </thead>
    <tbody data-role="dataWrapper">
      
    </tbody>
  </table>
</div>

<!-- Modal -->
<div class="modal fade" data-entity="formGeneralCleaning" data-method="tambah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Tambah Form General Cleaning</h5>
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
          <div class="form-group row">
            <div class="col-12 col-lg-6">
              <label>Area General Cleaning</label>
              <input type="hidden" name="area_general_cleaning_id">
              <input type="text" class="form-control" name="area_general_cleaning" readonly>
              <small class="form-text text-danger" data-role="feedback" data-field="area_general_cleaning"></small>
            </div>
            <div class="col-12 col-lg-6">
            </div>
          </div>
          <div class="form-group">
            <label>Kegiatan General Cleaning</label>
            <input type="hidden" name="kegiatan_general_cleaning_id">
            <textarea type="text" class="form-control" name="kegiatan_general_cleaning" readonly></textarea>
            <small class="form-text text-danger" data-role="feedback" data-field="kegiatan_general_cleaning"></small>
          </div>
          <div class="form-group">
            <label>Skor</label>
            <div class="form-group col">
              @for($i = 1; $i <= 5; $i++)
                <div class="form-check form-check-inline">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="skor" value="{{ $i }}">
                      {{ $i }}
                    </label>
                </div>
              @endfor
            </div>
          </div>
          <div class="form-group">
            <label>Keterangan</label>
            <textarea class="form-control form-control-sm" name="keterangan"></textarea>
            <small class="form-text text-danger" data-role="feedback" data-field="keterangan"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="formGeneralCleaning" data-method="ubah" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Ubah Form General Cleaning</h5>
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
          <div class="form-group row">
            <div class="col-12 col-lg-6">
              <label>Area General Cleaning</label>
              <input type="hidden" name="area_general_cleaning_id">
              <input type="text" class="form-control" name="area_general_cleaning" readonly>
              <small class="form-text text-danger" data-role="feedback" data-field="area_general_cleaning"></small>
            </div>
            <div class="col-12 col-lg-6">
            </div>
          </div>
          <div class="form-group">
            <label>Kegiatan General Cleaning</label>
            <input type="hidden" name="kegiatan_general_cleaning_id">
            <textarea type="text" class="form-control" name="kegiatan_general_cleaning" readonly></textarea>
            <small class="form-text text-danger" data-role="feedback" data-field="kegiatan_general_cleaning"></small>
          </div>
          <div class="form-group">
            <label>Skor</label>
            <div class="form-group col">
              @for($i = 1; $i <= 5; $i++)
                <div class="form-check form-check-inline">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="skor" value="{{ $i }}">
                      {{ $i }}
                    </label>
                </div>
              @endfor
            </div>
          </div>
          <div class="form-group">
            <label>Keterangan</label>
            <textarea class="form-control form-control-sm" name="keterangan"></textarea>
            <small class="form-text text-danger" data-role="feedback" data-field="keterangan"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Ubah</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" data-entity="formGeneralCleaning" data-method="hapus" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title">Hapus Form General Cleaning</h5>
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
<script src="{{ url('/assets/js/adapter/leader/AreaGeneralCleaning.js') }}"></script>
<script src="{{ url('/assets/js/adapter/leader/KegiatanGeneralCleaning.js') }}"></script>
<script src="{{ url('/assets/js/utils/Clock.js') }}"></script>
<script src="{{ url('/assets/js/adapter/leader/FormGeneralCleaning.js') }}"></script>
<script type="text/javascript">
  let formGeneralCleaning = FormGeneralCleaning();

  formGeneralCleaning.setQuery(@json($serializedQuery));
  formGeneralCleaning.responsiveContract();
</script>