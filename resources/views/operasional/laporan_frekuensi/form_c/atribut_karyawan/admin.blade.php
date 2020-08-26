<div class="row">
  <div class="col-12">
    <form data-role="search">
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">
              Tanggal Form
            </span>
          </div>
          <input type="date" class="form-control" name="tanggal_form" value="{{ $query['tanggal_form'] }}">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">
              Frekuensi Ideal
            </span>
          </div>
          <input type="number" class="form-control" name="frekuensi_ideal" value="{{ $query['frekuensi_ideal'] }}">
          <div class="input-group-prepend">
            <button type="submit" class="btn btn-primary" name="submit"><i class="fas fa-search"></i></button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

@if($query['submit'])
<div class="table-responsive mt-5">
  <table class="table table-bordered">
    <thead class="text-center">
      <th>#</th>
      <th>Cabang</th>
      <th>Total Petugas</th>
      <th>Total Sidak</th>
      <th>%</th>
      <th>Pelanggaran</th>
    </thead>
    <tbody>
      @foreach($cabangs as $cabang)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $cabang->cabang }}</td>
          <td>
            @php $clone = clone $formAtributKaryawanModels; @endphp
            {{ $clone->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->distinct('tugas_karyawan_id')->count() }}
          </td>
          <td>
            @php $clone = clone $formAtributKaryawanModels; @endphp
            {{ $clone->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->count() }}
          </td>
          <td>
            @php $clone = clone $formAtributKaryawanModels; @endphp
            {{ round(($clone->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->count() / $query['frekuensi_ideal']) * 100, 2) }} %
          </td>
          <td>
            @php $clone = clone $formAtributKaryawanModels; @endphp
            {{ $clone->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->whereHas('d.parameter_atribut_karyawan', function ($q) {$q->where('pelanggaran', 1);})->count() }}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endif