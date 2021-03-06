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
        <th rowspan="2">Thawing Ayam</th>
        <th colspan="{{ $itemGorengModels->count() }}">Goreng</th>
        <th rowspan="2">Masak Nasi</th>
        <th colspan="{{ $tipeProsesSambalModels->count() }}">Sambal</th>
        <th colspan="{{ $tipeProsesTepungModels->count() }}">Tepung</th>
        <th colspan="{{ $tipeProsesMinyakModels->count() }}">Minyak</th>
        <th colspan="{{ $tipeProsesMargarinModels->count() }}">Margarin</th>
        <th colspan="{{ $tipeProsesLPGModels->count() }}">LPG</th>
      </tr>
      <tr>
        @foreach($itemGorengModels as $itemGorengModel)
          <th>{{ $itemGorengModel->item_goreng }}</th>
        @endforeach
        @foreach($tipeProsesSambalModels as $tipeProsesSambalModel)
          <th>{{ $tipeProsesSambalModel->tipe_proses_sambal }}</th>
        @endforeach
        @foreach($tipeProsesTepungModels as $tipeProsesTepungModel)
          <th>{{ $tipeProsesTepungModel->tipe_proses_tepung }}</th>
        @endforeach
        @foreach($tipeProsesMinyakModels as $tipeProsesMinyakModel)
          <th>{{ $tipeProsesMinyakModel->tipe_proses_minyak }}</th>
        @endforeach
        @foreach($tipeProsesMargarinModels as $tipeProsesMargarinModel)
          <th>{{ $tipeProsesMargarinModel->tipe_proses_margarin }}</th>
        @endforeach
        @foreach($tipeProsesLPGModels as $tipeProsesLPGModel)
          <th>{{ $tipeProsesLPGModel->tipe_proses_lpg }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @foreach($cabangs as $cabang)
        @php
          $formThawingAyamModel = clone $formThawingAyamModels;
          $formGorengModel = clone $formGorengModels;
          $formMasakNasiModel = clone $formMasakNasiModels;
          $formSambalModel = clone $formSambalModels;
          $formTepungModel = clone $formTepungModels;
          $formMinyakModel = clone $formMinyakModels;
          $formMargarinModel = clone $formMargarinModels;
          $formLPGModel = clone $formLPGModels;
        @endphp
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $cabang->cabang }}</td>
          @php $clone = clone $formThawingAyamModel @endphp
          <td class="@if($clone->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->get()->count() == 0) table-danger @endif">
            @php $clone = clone $formThawingAyamModel @endphp
            {{ $clone->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->get()->count() }}
          </td>
          @foreach($itemGorengModels as $itemGorengModel)
            @php $clone = clone $formGorengModel; @endphp
            <td class="@if($clone->where('item_goreng_id', $itemGorengModel->id)->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->count() == 0) table-danger @endif">
              @php $clone = clone $formGorengModel; @endphp
              {{ $clone->where('item_goreng_id', $itemGorengModel->id)->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->count() }}
            </td>
          @endforeach
          @php $clone = clone $formMasakNasiModel; @endphp
          <td class="@if($clone->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->count() == 0) table-danger @endif">
            @php $clone = clone $formMasakNasiModel; @endphp
            {{ $clone->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->count() }}
          </td>
          @foreach($tipeProsesSambalModels as $tipeProsesSambalModel)
            @php $clone = clone $formSambalModel; @endphp
            <td class="@if($clone->where('tipe_proses_sambal_id', $tipeProsesSambalModel->id)->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->count() == 0) table-danger @endif">
              @php
                $clone = clone $formSambalModel;
              @endphp
              {{ $clone->where('tipe_proses_sambal_id', $tipeProsesSambalModel->id)->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->count() }}</td>
          @endforeach
          @foreach($tipeProsesTepungModels as $tipeProsesTepungModel)
            @php $clone = clone $formTepungModel; @endphp
            <td class="@if($clone->where('tipe_proses_tepung_id', $tipeProsesTepungModel->id)->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->count() == 0) table-danger @endif">
              @php
                $clone = clone $formTepungModel;
              @endphp
              {{ $clone->where('tipe_proses_tepung_id', $tipeProsesTepungModel->id)->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->count() }}</td>
          @endforeach
          @foreach($tipeProsesMinyakModels as $tipeProsesMinyakModel)
            @php $clone = clone $formMinyakModel; @endphp
            <td class="@if($clone->where('tipe_proses_minyak_id', $tipeProsesMinyakModel->id)->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->count() == 0) table-danger @endif">
              @php
                $clone = clone $formMinyakModel;
              @endphp
              {{ $clone->where('tipe_proses_minyak_id', $tipeProsesMinyakModel->id)->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->count() }}</td>
          @endforeach
          @foreach($tipeProsesMargarinModels as $tipeProsesMargarinModel)
            @php $clone = clone $formMargarinModel; @endphp
            <td class="@if($clone->where('tipe_proses_margarin_id', $tipeProsesMargarinModel->id)->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->count() == 0) table-danger @endif">
              @php
                $clone = clone $formMargarinModel;
              @endphp
              {{ $clone->where('tipe_proses_margarin_id', $tipeProsesMargarinModel->id)->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->count() }}</td>
          @endforeach
          @foreach($tipeProsesLPGModels as $tipeProsesLPGModel)
            @php $clone = clone $formLPGModel; @endphp
            <td class="@if($clone->where('tipe_proses_lpg_id', $tipeProsesLPGModel->id)->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->count() == 0) table-danger @endif">
              @php
                $clone = clone $formLPGModel;
              @endphp
              {{ $clone->where('tipe_proses_lpg_id', $tipeProsesLPGModel->id)->whereHas('tugas_karyawan', function ($q) use ($cabang) {$q->where('cabang_id', $cabang->id);})->count() }}</td>
          @endforeach
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endif