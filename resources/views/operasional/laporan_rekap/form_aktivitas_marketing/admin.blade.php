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
    <table class="table table-hover">
      <thead>
        <th>#</th>
        <th>Cabang</th>
        <th>Aktivitas Marketing</th>
        <th>Lokasi</th>
        <th>Item Marketing</th>
        <th>Qty</th>
        <th>Satuan</th>
        <th>Jam</th>
        <th>Petugas</th>
      </thead>
      <tbody>
        @foreach($cabangs as $cabang)
          @php
            $formAktivitasMarketings = $formAktivitasMarketingModels->whereHas('tugas_karyawan', function ($q) use ($cabang) {
              $q->where('cabang_id', $cabang->id);
            })->get();
            $rowspan = $formAktivitasMarketings->count();
            $rowspan = $rowspan == 0 ? 1 : $rowspan;
          @endphp
          <tr>
            <td rowspan="{{ $rowspan }}">
              {{ $loop->iteration }}
            </td>
            <td rowspan="{{ $rowspan }}">
              {{ $cabang->cabang }}
            </td>
            <td>{{ $formAktivitasMarketings[0]->aktivitas_marketing->aktivitas_marketing ?? '-' }}</td>
            <td>{{ $formAktivitasMarketings[0]->lokasi ?? '-' }}</td>
            <td>{{ $formAktivitasMarketings[0]->item_marketing->item_marketing ?? '-' }}</td>
            <td>{{ $formAktivitasMarketings[0]->qty ?? '-' }}</td>
            <td>{{ $formAktivitasMarketings[0]->satuan->satuan ?? '-' }}</td>
            <td>{{ $formAktivitasMarketings[0]->jam->jam ?? '-' }}</td>
            <td>{{ $formAktivitasMarketings[0]->tugas_karyawan->karyawan->nip ?? '' }} - {{ $formAktivitasMarketings[0]->tugas_karyawan->karyawan->nama_karyawan ?? '' }}</td>
          </tr>
          @if($rowspan > 1)
            @for($i = 1; $i < $rowspan; $i++)
              <tr>
                <td>{{ $formAktivitasMarketings[$i]->aktivitas_marketing->aktivitas_marketing ?? '-' }}</td>
                <td>{{ $formAktivitasMarketings[$i]->lokasi ?? '-' }}</td>
                <td>{{ $formAktivitasMarketings[$i]->item_marketing->item_marketing ?? '-' }}</td>
                <td>{{ $formAktivitasMarketings[$i]->qty ?? '-' }}</td>
                <td>{{ $formAktivitasMarketings[$i]->satuan->satuan ?? '-' }}</td>
                <td>{{ $formAktivitasMarketings[$i]->jam->jam ?? '-' }}</td>
                <td>{{ $formAktivitasMarketings[$i]->tugas_karyawan->karyawan->nip ?? '' }} - {{ $formAktivitasMarketings[0]->tugas_karyawan->karyawan->nama_karyawan ?? '' }}</td>
              </tr>
            @endfor
          @endif
        @endforeach
      </tbody>
    </table>
  </div>
@endif