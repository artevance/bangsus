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
      @foreach($qualityControlModels as $qualityControlModel)
        <th>{{ $qualityControlModel->quality_control }}</th>
      @endforeach
      <th>%</th>
    </thead>
    <tbody>
      @foreach($cabangs as $cabang)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $cabang->cabang }}</td>
          @foreach($qualityControlModels as $qualityControlModel)
            @php $clone = clone $formQualityControlModels; @endphp
            <td class="@if($clone->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->where('quality_control_id', $qualityControlModel->id)->get()->count() == 0) table-danger @endif">
              @php $clone = clone $formQualityControlModels; @endphp
              {{ $clone->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->where('quality_control_id', $qualityControlModel->id)->get()->count() }}
            </td>
          @endforeach
          @php $clone = clone $formQualityControlModels; @endphp
          <td>
            {{ ($clone->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->get()->count() / ($qualityControlModels->count() * $query['frekuensi_ideal'])) * 100 }} %
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endif