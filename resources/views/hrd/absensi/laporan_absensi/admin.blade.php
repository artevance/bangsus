<div class="row mt-5">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="card">
      <div class="card-body">
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
                  <select class="form-control col-6" name="cabang_id">
                    @foreach($cabangs as $cabang)
                      <option value="{{ $cabang->id }}" @if($cabang->id == $query['cabang_id']) {{ 'selected' }} @endif>
                        {{ $cabang->kode_cabang }} - {{ $cabang->cabang }}
                      </option>
                    @endforeach
                  </select>
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                      Tipe Absensi
                    </span>
                  </div>
                  <select class="form-control" name="tipe_absensi_id">
                    @foreach($tipeAbsensis as $tipeAbsensi)
                      <option value="{{ $tipeAbsensi->id }}" @if($tipeAbsensi->id == $query['tipe_absensi_id']) {{ 'selected' }} @endif>
                        {{ $tipeAbsensi->tipe_absensi }}
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
                  <div class="input-group-prepend">
                    <button type="submit" class="btn btn-primary" name="submit"><i class="fas fa-search"></i></button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        @if($generated)
          @if( ! empty($results))
            <div class="table-responsive mt-5">
              <table class="table table-bordered table-hover">
                <thead>
                  <th>#</th>
                  <th>NIP</th>
                  <th>Nama Karyawan</th>
                  @for($i = strtotime($query['tanggal_awal']); $i <= strtotime($query['tanggal_akhir']); $i += 86400)
                    <th>{{ date('Y-m-d', $i) }}</th>
                  @endfor
                  <th>Total Jadwal</th>
                  <th>Total Masuk</th>
                </thead>
                <tbody>
                  @foreach($results as $data)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $data->karyawan->nip }}</td>
                      <td>{{ $data->karyawan->nama_karyawan }}</td>
                      @php
                        $j = 0;
                        $jamJadwalCount = 0;
                        $jamAbsenCount = 0;
                      @endphp
                      @for($i = strtotime($query['tanggal_awal']); $i <= strtotime($query['tanggal_akhir']); $i += 86400)
                        @isset($data->absensi[$j])
                          @if(strtotime($data->absensi[$j]->tanggal_absensi) == $i)
                            @php
                              $jamJadwalCount += is_null($data->absensi[$j]->jam_jadwal) ? 0 : 1;
                              $jamAbsenCount += is_null($data->absensi[$j]->jam_absen) ? 0 : 1;
                            @endphp
                            <td class="
                              @if( ! is_null($data->absensi[$j]->keterlambatan))
                                table-danger
                              @elseif(is_null($data->absensi[$j]->jam_jadwal) && ! is_null($data->absensi[$j]->jam_absen))
                                table-warning
                              @elseif(is_null($data->absensi[$j]->jam_absen) && ! is_null($data->absensi[$j]->jam_jadwal))
                                table-info
                              @endif">
                              Jam Jadwal: {{ $data->absensi[$j]->jam_jadwal }}<br>
                              Jam Absen: {{ $data->absensi[$j]->jam_absen }}<br>
                              Terlambat: <b>{{ $data->absensi[$j]->keterlambatan }}</b>
                            </td>
                            @php
                              $j++;
                            @endphp
                          @else
                            <td></td>
                          @endif
                        @else
                          <td></td>
                        @endisset
                      @endfor
                      <td>{{ $jamJadwalCount ?? 0 }}</td>
                      <td>{{ $jamAbsenCount ?? 0 }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="alert alert-warning mt-5" role="alert">
              Data yang anda minta tidak ditemukan
            </div>
          @endif
        @endif
      </div>
    </div>
  </div>
</div>