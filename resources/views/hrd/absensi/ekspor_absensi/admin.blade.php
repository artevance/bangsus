<div class="row mt-5">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="card-title">
          Ekspor Absensi
        </div>
        <form target="_blank" action="{{ url('/hrd/absensi/ekspor_absensi/ekspor') }}" method="post">
          @csrf
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">
                  Cabang
                </span>
              </div>
              <select class="form-control col-6" name="cabang_id">
                <option value="*">Semua Cabang</option>
                @foreach($cabangs as $cabang)
                  <option value="{{ $cabang->id }}" @if($cabang->id == $query['cabang_id']) {{ 'selected' }} @endif>
                    {{ $cabang->kode_cabang }} - {{ $cabang->cabang }}
                  </option>
                @endforeach
              </select>
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">
                  Tanggal Awal
                </span>
              </div>
              <input type="date" class="form-control" name="tanggal_awal" value="{{ $query['tanggal_awal'] }}">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">
                  Tanggal Akhir
                </span>
              </div>
              <input type="date" class="form-control" name="tanggal_akhir" value="{{ $query['tanggal_akhir'] }}">
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">
                  Off
                </span>
              </div>
              <input type="number" class="form-control" name="off" value="4">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">
                  Nominal Denda (Per Menit)
                </span>
              </div>
              <input type="number" class="form-control" name="nominal_denda" value="5000">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">
                  Nominal Maksimal Denda
                </span>
              </div>
              <input type="number" class="form-control" name="nominal_maksimal_denda" value="25000">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">
                  Hari Kerja Maksimal
                </span>
              </div>
              <input type="number" class="form-control" name="hari_maksimal_masuk">
              <div class="input-group-prepend">
                <button type="submit" class="btn btn-primary" name="submit">Ekspor</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>