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
<div class="table-responsive mt-5">
  <table class="table table-hover">
    <thead>
      <th>#</th>
      <th>Kelompok Foto</th>
      <th>Denda Foto</th>
      <th>Nominal</th>
    </thead>
    <tbody>
      @foreach($results as $result)
        @foreach($result->d as $d)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $result->form_foto->kelompok_foto->kelompok_foto }}</td>
            <td>{{ $d->denda_foto->denda_foto }}</td>
            <td>{{ $d->nominal }}</td>
          </tr>
        @endforeach
      @endforeach
      <tr>
        <th class="text-center" colspan="3">Grand Total</th>
        <th>{{ $results->sum('total') }}</th>
      </tr>
    </tbody>
  </table>
</div>