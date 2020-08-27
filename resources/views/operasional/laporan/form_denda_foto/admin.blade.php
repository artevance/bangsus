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
              Tipe Laporan
            </span>
          </div>
          <select class="form-control" name="tipe_laporan">
            <option value="1" @if($query['tipe_laporan'] == 1) selected @endif>Per Kelompok Foto</option>
            <option value="2" @if($query['tipe_laporan'] == 2) selected @endif>Per Denda</option>
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
  @switch($query['tipe_laporan'])
    @case(2)
      <table class="table table-hover">
        <thead>
          <th>#</th>
          <th>Foto</th>
          <th>Kelompok Foto</th>
          <th>Denda Foto</th>
          <th>Nominal</th>
        </thead>
        <tbody>
          @foreach($formDendaFotoModels as $formDendaFotoModel)
            <tr>
              <td rowspan="{{ $formDendaFotoModel->d->count() == 0 ? 1 : $formDendaFotoModel->d->count() }}">{{ $loop->iteration }}</td>
              <td rowspan="{{ $formDendaFotoModel->d->count() == 0 ? 1 : $formDendaFotoModel->d->count() }}">
                <img src="{{ url('/gambar/' . $formDendaFotoModel->form_foto->gambar_id) }}"> - <a href="{{ url('/gambar/' . $formDendaFotoModel->form_foto->gambar_id) }}">Link Foto</a>
              </td>
              <td rowspan="{{ $formDendaFotoModel->d->count() == 0 ? 1 : $formDendaFotoModel->d->count() }}">{{ $formDendaFotoModel->form_foto->kelompok_foto->kelompok_foto }}</td>
              <td>{{ $formDendaFotoModel->d[0]->denda_foto->denda_foto ?? '-' }}</td>
              <td>{{ $formDendaFotoModel->d[0]->nominal ?? '' }}</td>
            </tr>
            @foreach($formDendaFotoModel->d as $d)
              @if($loop->index == 0)
                @continue
              @endif
              <tr>
                <td>{{ $d->denda_foto->denda_foto }}</td>
                <td>{{ $d->nominal }}</td>
              </tr>
            @endforeach
          @endforeach
          <tr>
            <th class="text-center" colspan="4">Grand Total</th>
            <th>{{ $formDendaFotoModels->sum('total') }}</th>
          </tr>
        </tbody>
      </table>
    @break
    @case(1)
    @default
      <table class="table table-hover">
        <thead>
          <th>#</th>
          <th>Kelompok Foto</th>
          <th>Denda</th>
          <th>Total</th>
        </thead>
        <tbody>
          @php
            $grandTotal = 0;
          @endphp
          @foreach($kelompokFotoModels as $kelompokFotoModel)
            <tr>
              <td rowspan="{{ $kelompokFotoModel->denda_foto->count() == 0 ? 1 : $kelompokFotoModel->denda_foto->count() }}">{{ $loop->iteration }}</td>
              <td rowspan="{{ $kelompokFotoModel->denda_foto->count() == 0 ? 1 : $kelompokFotoModel->denda_foto->count() }}">{{ $kelompokFotoModel->kelompok_foto }}</td>
              <td>{{ $kelompokFotoModel->denda_foto[0]->denda_foto ?? '(tidak ada denda)' }}</td>
              @php
                $nominal = isset($kelompokFotoModel->denda_foto[0]) ? $kelompokFotoModel->denda_foto[0]->form_denda_foto_d->sum('nominal') : 0
              @endphp
              <td>{{ $nominal }}</td>
              @php
                $grandTotal += $nominal;
              @endphp
            </tr>
            @foreach($kelompokFotoModel->denda_foto as $dendaFotoModel)
              @if ($loop->index == 0)
                @continue
              @endif
              <tr>
                <td>{{ $dendaFotoModel->denda_foto }}</td>
                @php
                  $nominal = $dendaFotoModel->form_denda_foto_d->sum('nominal') ?? 0;
                @endphp
                <td>{{ $nominal }}</td>
                @php
                  $grandTotal += $nominal;
                @endphp
              </tr>
            @endforeach
          @endforeach
          <tr>
            <th class="text-center" colspan="3">Grand Total</th>
            <th>{{ $grandTotal }}</th>
          </tr>
        </tbody>
      </table>
    @break
  @endswitch
</div>