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
      <tr>
        <th rowspan="2">#</th>
        <th rowspan="2">Cabang</th>
        @php $clone = clone $kegiatanKebersihanModels; @endphp
        <th colspan="{{ $clone->count() }}">Frekuensi</th>
        <th rowspan="2">Total Frekuensi</th>
        <th rowspan="2">%</th>
        <th rowspan="2">Total Poin</th>
        <th rowspan="2">Skor Max</th>
        <th rowspan="2">Skor Rata-rata</th>
      </tr>
      <tr>
        @foreach($kegiatanKebersihanModels as $kegiatanKebersihanModel)
          <th>{{ $loop->iteration }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @foreach($cabangs as $cabang)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $cabang->cabang }}</td>
          @foreach($kegiatanKebersihanModels as $kegiatanKebersihanModel)
            @php $clone = clone $formKebersihanModels; @endphp
            <td class="@if($clone->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->where('kegiatan_kebersihan_id', $kegiatanKebersihanModel->id)->get()->count() == 0) table-danger @endif">
              @php $clone = clone $formKebersihanModels; @endphp
              {{ $clone->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->where('kegiatan_kebersihan_id', $kegiatanKebersihanModel->id)->get()->count() }}
            </td>
          @endforeach
          @php $clone = clone $formKebersihanModels; @endphp
          <td class="@if($clone->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->get()->count() == 0) table-danger @endif">
            @php
              $clone = clone $formKebersihanModels;
              $totalFrekuensi = $clone->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->get()->count()
            @endphp
            {{ $totalFrekuensi }}
          </td>
          <td>
            @php $clone = clone $formKebersihanModels; @endphp
            {{ round(($totalFrekuensi / $query['frekuensi_ideal']) * 100, 2) }} %
          </td>
          <td>
            @php
              $clone = clone $formKebersihanModels;
              $totalSkor = $clone->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->get()->sum('skor');
            @endphp
            {{ $totalSkor }}
          </td>
          <td>
            @php
              $clone = clone $formKebersihanModels;
              $skorMax = $clone->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->get()->count() * 5;
            @endphp
            {{ $skorMax }}
          </td>
          <td>
            {{ $totalFrekuensi != 0 ? round($totalSkor / $totalFrekuensi, 2) : '-' }}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endif