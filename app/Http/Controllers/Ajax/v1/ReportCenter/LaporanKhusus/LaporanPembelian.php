<?php

namespace App\Http\Controllers\Ajax\v1\ReportCenter\LaporanKhusus;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Pembelian;
use App\Http\Models\Barang;

class LaporanPembelian
{
  protected $codes = [
    'BI-00022',
    'BM-00016',
    'PR-00022',
    'BI-00009',
    'SY-00021',
    'SY-00010',
    'SY-00008',
    'SY-00024',
    'SY-00022',
    'SY-00080',
    'BM-00001',
    'SY-00132',
    'SY-00036',
    'SY-00019',
    'BM-00052',
    'BI-00025',
    'BB-00014',
  ];

  public function index(Request $request)
  {
    $tanggal = $request->query('tanggal');
    $pembelian = Pembelian::whereDate('dTgl', '=', $tanggal)
      ->whereIn('cKdBrg', $this->codes)
      ->get();
    $pembelian->each(function ($p) {
      $barang = Barang::where('kode_barang', $p->cKdBrg)->first();

      if ($p->nqty2 != 0) {
        $p->nHarga1 /= $barang->rasio_dua ?? 1;
      }
      if ($p->nqty3 != 0) {
        $p->nHarga1 /= $barang->rasio_tiga ?? 1;
      }
      if ($p->nqty4 != 0) {
        $p->nHarga1 /= $barang->rasio_empat ?? 1;
      }
      if ($p->nqty5 != 0) {
        $p->nHarga1 /= $barang->rasio_lima ?? 1;
      }
    });

    $branches = $pembelian->pluck('cKetGudang')->unique()->values()->all();
    $items = $pembelian->pluck('cNmBrg')->unique()->values()->all();

    $rows = [];

    foreach ($items as $item) {
      $row = [];
      $row['nama_barang'] = $item;
      $row['lowest_price'] = (float) $pembelian->where('cNmBrg', $item)->min('nHarga1');
      $rowPrices = [];
      foreach ($branches as $branch) {
        $rowPrice = $pembelian
          ->where('cKetGudang', $branch)
          ->where('cNmBrg', $item)
          ->min('nHarga1');

        $rowPrice = ! is_null($rowPrice) ? (float) $rowPrice : '-';
        $rowPrices[] = $rowPrice;
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
}
