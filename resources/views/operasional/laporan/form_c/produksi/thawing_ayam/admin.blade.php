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
<div class="table-responsive mt-2">
  <table class="table table-hover">
    <thead>
      <th>#</th>
      <th>NIP</th>
      <th>Nama Karyawan</th>
      <th>Jam</th>
      <th>Qty</th>
      <th>Satuan</th>
      <th>Supplier</th>
    </thead>
    <tbody>
      @foreach($results as $result)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $result->tugas_karyawan->karyawan->nip }}</td>
          <td>{{ $result->tugas_karyawan->karyawan->nama_karyawan }}</td>
          <td>{{ $result->jam }}</td>
          <td>{{ $result->qty }}</td>
          <td>{{ $result->satuan->satuan }}</td>
          <td>{{ $result->supplier->supplier }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>