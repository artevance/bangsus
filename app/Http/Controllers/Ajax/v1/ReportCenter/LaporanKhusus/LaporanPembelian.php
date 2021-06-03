<?php

namespace App\Http\Controllers\Ajax\v1\ReportCenter\LaporanKhusus;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Pembelian;
use App\Http\Models\Barang;

class LaporanPembelian
{
  protected $items = [
    [
      'kode_barang' => 'BI-00022',
      'level_satuan' => 2,
    ],
    [
      'kode_barang' => 'BI-00016',
      'level_satuan' => 1,
    ],
    [
      'kode_barang' => 'PR-00022',
      'level_satuan' => 2,
    ],
    [
      'kode_barang' => 'BI-00009',
      'level_satuan' => 1,
    ],
    [
      'kode_barang' => 'SY-00021',
      'level_satuan' => 2,
    ],
    [
      'kode_barang' => 'SY-00010',
      'level_satuan' => 2,
    ],
    [
      'kode_barang' => 'SY-00008',
      'level_satuan' => 2,
    ],
    [
      'kode_barang' => 'SY-00024',
      'level_satuan' => 2,
    ],
    [
      'kode_barang' => 'SY-00022',
      'level_satuan' => 2,
    ],
    [
      'kode_barang' => 'SY-00080',
      'level_satuan' => 2,
    ],
    [
      'kode_barang' => 'BM-00001',
      'level_satuan' => 2,
    ],
    [
      'kode_barang' => 'SY-00132',
      'level_satuan' => 2,
    ],
    [
      'kode_barang' => 'SY-00036',
      'level_satuan' => 2,
    ],
    [
      'kode_barang' => 'SY-00019',
      'level_satuan' => 2,
    ],
    [
      'kode_barang' => 'BM-00052',
      'level_satuan' => 2,
    ],
    [
      'kode_barang' => 'BI-00025',
      'level_satuan' => 1,
    ],
    [
      'kode_barang' => 'BB-00014',
      'level_satuan' => 2,
    ],
    [
      'kode_barang' => 'BM-00009',
      'level_satuan' => 1,
    ],
  ];

  public function byCabang(Request $request)
  {
    $cabang = $request->query('cabang');

    $tanggal = $request->query('tanggal');
    $pembelian = Pembelian::whereDate('dTgl', '=', $tanggal)
      ->whereIn('cKdBrg', collect($this->items)->pluck('kode_barang')->all())
      ->get();
    $pembelian->each(function ($p) {
      $barang = Barang::where('kode_barang', $p->cKdBrg)->first();
      $levelSatuan = collect($this->items)->where('kode_barang', $p->cKdBrg)
        ->min('level_satuan');
      $levelPembelian = 0;
      switch (true) {
        case $p->nqty1 != 0:
          $levelPembelian = 1;
          break;
        case $p->nqty2 != 0:
          $levelPembelian = 2;
          break;
        case $p->nqty3 != 0:
          $levelPembelian = 3;
          break;
        case $p->nqty4 != 0:
          $levelPembelian = 4;
          break;
        case $p->nqty5 != 0:
          $levelPembelian = 5;
          break;
      }

      if ($levelSatuan != $levelPembelian) {
        if ($levelSatuan > $levelPembelian) {
          for ($i = $levelPembelian; $i <= $levelSatuan; $i++) {
            $rasio = 1;
            switch ($i) {
              case 2:
                $rasio = $barang->rasio_dua ?? 1;
                break;
              case 3:
                $rasio = $barang->rasio_tiga ?? 1;
                break;
              case 4:
                $rasio = $barang->rasio_empat ?? 1;
                break;
              case 5:
                $rasio = $barang->rasio_lima ?? 1;
                break;
            }
            $p->nHarga1 *= $rasio;
          }
        } else {
          for ($i = $levelSatuan + 1; $i <= $levelPembelian; $i++) {
            $rasio = 1;
            switch ($i) {
              case 2:
                $rasio = $barang->rasio_dua ?? 1;
                break;
              case 3:
                $rasio = $barang->rasio_tiga ?? 1;
                break;
              case 4:
                $rasio = $barang->rasio_empat ?? 1;
                break;
              case 5:
                $rasio = $barang->rasio_lima ?? 1;
                break;
            }
            try {
              $p->nHarga1 /= $rasio;
            } catch (\Exception $e) {
              dd($barang, $rasio, $levelSatuan, $levelPembelian, $i);
            }
          }
        }
      }

      $p->nHarga1 = round($p->nHarga1, 2);
      $p->levelSatuan = $levelSatuan;
      $p->levelPembelian = $levelPembelian;
    });

    $branches = $cabang == '*'
      ? $pembelian->pluck('cKetGudang')->unique()->values()->all()
      : [$cabang];
    $items = $pembelian->pluck('cNmBrg')->unique()->values()->all();

    $rows = [];

    foreach ($items as $item) {
      $row = [];
      $row['nama_barang'] = $item;
      $barang = Barang::where('nama_barang', $item)->first();
      $row['lowest_price'] = $barang->harga_standar ?? round((float) $pembelian->where('cNmBrg', $item)->min('nHarga1'), 2);
      $rowPrices = [];
      foreach ($branches as $branch) {
        $rowPrice = $pembelian
          ->where('cKetGudang', $branch)
          ->whereIn('cNmBrg', $item)
          ->min('nHarga1');

        $rowPrice = ! is_null($rowPrice) ? (float) $rowPrice : '-';
        $percentage = $rowPrice == '-' ? null : ($rowPrice - $row['lowest_price']) / $rowPrice * 100;

        if ($percentage == null) :
          $range = 0;
        elseif ($percentage < 5) :
          $range = 1;
        elseif ($percentage >= 5 && $percentage < 10) :
          $range = 2;
        elseif ($percentage >= 10 && $percentage < 15) :
          $range = 3;
        elseif ($percentage >= 15 && $percentage < 20) :
          $range = 4;
        else :
          $range = 5;
        endif;

        $rowPrices[] = [
          'price' => $rowPrice,
          'range' => $range,
        ];
      }
      $row['prices'] = $rowPrices;
      $rows[] = $row;
    }


    return [
      'data' => $pembelian,
      'branches' => $branches,
      'items' => $rows,
    ];
  }

  public function byBarang(Request $request)
  {
    $kodeBarang = $request->query('kode_barang') != '*'
      ? [$request->query('kode_barang')]
      : collect($this->items)->pluck('kode_barang')->all();

    $tanggal = $request->query('tanggal');
    $pembelian = Pembelian::whereDate('dTgl', '=', $tanggal)
      ->whereIn('cKdBrg', $kodeBarang)
      ->get();
    $pembelian->each(function ($p) {
      $barang = Barang::where('kode_barang', $p->cKdBrg)->first();
      $levelSatuan = collect($this->items)->where('kode_barang', $p->cKdBrg)
        ->min('level_satuan');
      $levelPembelian = 0;
      switch (true) {
        case $p->nqty1 != 0:
          $levelPembelian = 1;
          break;
        case $p->nqty2 != 0:
          $levelPembelian = 2;
          break;
        case $p->nqty3 != 0:
          $levelPembelian = 3;
          break;
        case $p->nqty4 != 0:
          $levelPembelian = 4;
          break;
        case $p->nqty5 != 0:
          $levelPembelian = 5;
          break;
      }

      if ($levelSatuan != $levelPembelian) {
        if ($levelSatuan > $levelPembelian) {
          for ($i = $levelPembelian; $i <= $levelSatuan; $i++) {
            $rasio = 1;
            switch ($i) {
              case 2:
                $rasio = $barang->rasio_dua ?? 1;
                break;
              case 3:
                $rasio = $barang->rasio_tiga ?? 1;
                break;
              case 4:
                $rasio = $barang->rasio_empat ?? 1;
                break;
              case 5:
                $rasio = $barang->rasio_lima ?? 1;
                break;
            }
            $p->nHarga1 *= $rasio;
          }
        } else {
          for ($i = $levelSatuan + 1; $i <= $levelPembelian; $i++) {
            $rasio = 1;
            switch ($i) {
              case 2:
                $rasio = $barang->rasio_dua ?? 1;
                break;
              case 3:
                $rasio = $barang->rasio_tiga ?? 1;
                break;
              case 4:
                $rasio = $barang->rasio_empat ?? 1;
                break;
              case 5:
                $rasio = $barang->rasio_lima ?? 1;
                break;
            }
            try {
              $p->nHarga1 /= $rasio;
            } catch (\Exception $e) {
              dd($barang, $rasio, $levelSatuan, $levelPembelian, $i, $p);
            }
          }
        }
      }

      $p->nHarga1 = round($p->nHarga1, 2);
    });

    $branches = $pembelian->pluck('cKetGudang')->unique()->values()->all();
    $items = $pembelian->pluck('cNmBrg')->unique()->values()->all();

    $rows = [];

    foreach ($items as $item) {
      $row = [];
      $row['nama_barang'] = $item;
      $barang = Barang::where('nama_barang', $item)->first();
      $row['lowest_price'] = $barang->harga_standar ?? round((float) $pembelian->where('cNmBrg', $item)->min('nHarga1'), 2);
      $rowPrices = [];
      foreach ($branches as $branch) {
        $rowPrice = $pembelian
          ->where('cKetGudang', $branch)
          ->where('cNmBrg', $item)
          ->min('nHarga1');

        $rowPrice = ! is_null($rowPrice) ? (float) $rowPrice : '-';
        $percentage = $rowPrice == '-' ? null : ($rowPrice - $row['lowest_price']) / $rowPrice * 100;

        if ($percentage == null) :
          $range = 0;
        elseif ($percentage < 5) :
          $range = 1;
        elseif ($percentage >= 5 && $percentage < 10) :
          $range = 2;
        elseif ($percentage >= 10 && $percentage < 15) :
          $range = 3;
        elseif ($percentage >= 15 && $percentage < 20) :
          $range = 4;
        else :
          $range = 5;
        endif;

        $rowPrices[] = [
          'price' => $rowPrice,
          'range' => $range,
        ];
      }
      $row['prices'] = $rowPrices;
      $rows[] = $row;
    }


    return [
      'data' => $pembelian,
      'branches' => $branches,
      'items' => $rows,
    ];
  }

  public function byRange(Request $request)
  {
    $kodeBarang = $request->query('kode_barang') != '*'
      ? [$request->query('kode_barang')]
      : collect($this->items)->pluck('kode_barang')->all();

    $tanggalAwal = $request->query('tanggal_awal');
    $tanggalAkhir = $request->query('tanggal_akhir');
    $tanggalAwalTimestamp = strtotime($tanggalAwal);
    $tanggalAkhirTimestamp = strtotime($tanggalAkhir);

    $barangs = Barang::all();
    $pembelians = Pembelian::whereBetween('dTgl', [$tanggalAwal, $tanggalAkhir])
      ->whereIn('cKdBrg', $kodeBarang)
      ->get();

    $pembelians->each(function ($p) use ($barangs) {
      $barang = with(clone $barangs)->where('kode_barang', $p->cKdBrg)->first();
      $levelSatuan = collect($this->items)->where('kode_barang', $p->cKdBrg)
        ->min('level_satuan');
      $levelPembelian = 0;
      switch (true) {
        case $p->nqty1 != 0:
          $levelPembelian = 1;
          break;
        case $p->nqty2 != 0:
          $levelPembelian = 2;
          break;
        case $p->nqty3 != 0:
          $levelPembelian = 3;
          break;
        case $p->nqty4 != 0:
          $levelPembelian = 4;
          break;
        case $p->nqty5 != 0:
          $levelPembelian = 5;
          break;
      }

      if ($levelSatuan != $levelPembelian) {
        if ($levelSatuan > $levelPembelian) {
          for ($i = $levelPembelian; $i <= $levelSatuan; $i++) {
            $rasio = 1;
            switch ($i) {
              case 2:
                $rasio = $barang->rasio_dua ?? 1;
                break;
              case 3:
                $rasio = $barang->rasio_tiga ?? 1;
                break;
              case 4:
                $rasio = $barang->rasio_empat ?? 1;
                break;
              case 5:
                $rasio = $barang->rasio_lima ?? 1;
                break;
            }
            $p->nHarga1 *= $rasio;
          }
        } else {
          for ($i = $levelSatuan + 1; $i <= $levelPembelian; $i++) {
            $rasio = 1;
            switch ($i) {
              case 2:
                $rasio = $barang->rasio_dua ?? 1;
                break;
              case 3:
                $rasio = $barang->rasio_tiga ?? 1;
                break;
              case 4:
                $rasio = $barang->rasio_empat ?? 1;
                break;
              case 5:
                $rasio = $barang->rasio_lima ?? 1;
                break;
            }
            try {
              $p->nHarga1 /= $rasio;
            } catch (\Exception $e) {
              dd($barang, $rasio, $levelSatuan, $levelPembelian, $i);
            }
          }
        }
      }

      $p->nHarga1 = round($p->nHarga1, 2);
    });
    $branches = $pembelians->pluck('cKetGudang')->unique()->values()->all();
    $items = $pembelians->pluck('cNmBrg')->unique()->values()->all();

    $dates = [];
    for ($i = $tanggalAwalTimestamp; $i <= $tanggalAkhirTimestamp; $i += 86400) {
      $dates[] = date('Y-m-d', $i);
    }

    $data = [];
    foreach ($items as $item) {
      $barang = [];
      $barang['nama_barang'] = $item;
      $barang = Barang::where('nama_barang', $item)->first();
      $row['lowest_price'] = $barang->harga_standar ?? round((float) $pembelian->where('cNmBrg', $item)->min('nHarga1'), 2);
      $barangCabang = [];
      $cwlps = $pembelians->where('cNmBrg', $item)->where('nHarga1', $barang['lowest_price'])->all();

      foreach ($cwlps as $cwlp) {
        $barangCabang[] = [
          'nama_cabang' => $cwlp->cKetGudang,
          'tanggal' => date('Y-m-d', strtotime($cwlp->dTgl)),
        ];
      }
      $barang['cabang'] = $barangCabang;

      $rows = [];
      foreach ($branches as $branch) {
        $row = [];
        for ($i = $tanggalAwalTimestamp; $i <= $tanggalAkhirTimestamp; $i += 86400) {
          $tanggal = date('Y-m-d', $i);
          $pembelian = with(clone $pembelians)->where('dTgl', '=', $tanggal . ' 00:00:00');

          $rowPrice = $pembelian
            ->where('cKetGudang', $branch)
            ->where('cNmBrg', $item)
            ->min('nHarga1');

          $rowPrice = ! is_null($rowPrice) ? (float) $rowPrice : '-';
          $percentage = $rowPrice == '-' ? null : ($rowPrice - $barang['lowest_price']) / $rowPrice * 100;

          if ($percentage == null) :
            $range = 0;
          elseif ($percentage < 5) :
            $range = 1;
          elseif ($percentage >= 5 && $percentage < 10) :
            $range = 2;
          elseif ($percentage >= 10 && $percentage < 15) :
            $range = 3;
          elseif ($percentage >= 15 && $percentage < 20) :
            $range = 4;
          else :
            $range = 5;
          endif;

          $row[] = [
            'price' => $rowPrice,
            'range' => $range,
          ];
        }
        $rows[] = $row;
      }

      $data[] = [
        'barang' => $barang,
        'branches' => $branches,
        'items' => $rows,
      ];
    }

    return [
      'data' => $data,
      'dates' => $dates,
    ];
  }

  public function cabang(Request $request)
  {
    return \DB::connection('bigguy')->table('tbpembelianNew')
      ->select('cKetGudang')
      ->groupBy('cKetGudang')
      ->get();
  }

  public function barang(Request $request)
  {
    return Barang::whereIn('kode_barang',
        collect($this->items)->pluck('kode_barang')->all()
      )
      ->get();
  }
}
