<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\HargaBarang as HargaBarangModel;
use App\Http\Models\HargaBarangD;
use App\Http\Models\Barang;
use App\Http\Models\Cabang;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

use Carbon\Carbon;

class HargaBarang extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(HargaBarangModel::with([
        'cabang',
        'd'
      ]))
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if (is_null(HargaBarangModel::find($id))) return $this->response(404);

    return $this->data(HargaBarangModel::with([
      'cabang',
      'd'
    ])->find($id))->response(200);
  }

  public function report(Request $request, $id)
  {
    if (is_null(HargaBarangModel::find($id))) return $this->response(404);

    $hargaBarang = HargaBarangModel::with([
      'cabang',
      'd'
    ])->find($id);

    $spreadsheet = new Spreadsheet;
    $sheet = $spreadsheet->getActiveSheet();

    $container = [
      ['Harga Barang'],
      [$hargaBarang->cabang->kode_cabang . ' - ' . $hargaBarang->cabang->cabang],
      [$hargaBarang->tanggal_form],
      [],
      ['Kode Barang', 'Nama Barang', 'Qty', 'Satuan'],
    ];

    foreach ($hargaBarang->d as $detail) {
      switch ($detail->level_satuan) {
        case 1 :
          $satuan = $detail->barang->satuan->satuan;
          break;
        case 2 :
          $satuan = $detail->barang->satuan_dua->satuan;
          break;
        case 3 :
          $satuan = $detail->barang->satuan_tiga->satuan;
          break;
        case 4 :
          $satuan = $detail->barang->satuan_empat->satuan;
          break;
        case 5 :
          $satuan = $detail->barang->satuan_lima->satuan;
          break;
      }

      $container[] = [
        $detail->barang->kode_barang, $detail->barang->nama_barang, $detail->qty, $satuan
      ];
    }

    $sheet->fromArray(
        $container,
        null,
        'A1'
      );

    $filename = 'Harga Barang - ' . uniqid() . '.xlsx';
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save($filename);

    return response()
        ->download($filename)->deleteFileAfterSend();
  }

  public function dailyBranch(Request $request)
  {
    $query = [
      'tanggal_form' => $request->input('tanggal_form', date('Y-m-d'))
    ];

    return $this
      ->data(
        HargaBarangModel::with([
          'd'
        ])
        ->where('tanggal_form', $query['tanggal_form'])
        ->orderBy('jam')
        ->get()
      )->response(200);
  }

  public function daily(Request $request)
  {
    $query = [
      'tanggal_form' => $request->input('tanggal_form', date('Y-m-d'))
    ];

    return $this
      ->data(
        HargaBarangModel::with([
          'd'
        ])
        ->where('tanggal_form', $query['tanggal_form'])
        ->orderBy('jam')
        ->get()
      )->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'd'
    ), [
      'd.*.barang_id' => 'required|exists:barang,id',
      'd.*.level_satuan' => 'required',
      'd.*.harga_lama' => 'required|max:10000000000',
      'd.*.harga_barang' => 'required|max:10000000000',
      'd.*.keterangan' => 'nullable|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    // if (Carbon::now()->isAfter(Carbon::create(date('Y'), date('m'), date('d'), 10, 0, 0)) || Carbon::now()->isBefore(Carbon::create(date('Y'), date('m'), date('d'), 8, 0, 0))) {
    //   return $this->errors(['cabang_id', 'Waktu sudah habis'])->response(422);
    // }

    $hargaBarangModel = new HargaBarangModel;
    $hargaBarangModel->tanggal_form = date('Y-m-d');
    $hargaBarangModel->jam = date('H:i:s');
    $hargaBarangModel->accepted = false;
    $hargaBarangModel->approve = false;
    $hargaBarangModel->user_id = $request->user()->id;
    $hargaBarangModel->save();

    foreach ($request->input('d') as $d) {
      $barang = Barang::find($d['barang_id']);
      $constant = 1;

      for ($i = 1; $i <= $d['level_satuan']; $i++) {
        switch ($i) {
          case 1 :
            $constant = $constant;
            break;
          case 2 :
            $constant *= $barang->rasio_dua;
            break;
          case 3 :
            $constant *= $barang->rasio_tiga;
            break;
          case 4 :
            $constant *= $barang->rasio_empat;
            break;
          case 5 :
            $constant *= $barang->rasio_lima;
            break;
        }
      }

      $detailModel = new HargaBarangD;
      $detailModel->harga_barang_id = $hargaBarangModel->id;
      $detailModel->barang_id = $d['barang_id'];
      $detailModel->level_satuan = $d['level_satuan'];
      $detailModel->qty_konversi = $d['qty'] * $constant;
      $detailModel->harga_lama = $d['harga_lama'];
      $detailModel->harga_barang = $d['harga_barang'];
      $detailModel->approve = 0;
      $detailModel->accepted = 0;
      $detailModel->keterangan = $d['keterangan'] ?? '';
      $detailModel->save();
    }
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'd'
    ), [
      'id' => 'required|exists:harga_barang,id',
      'd.*.barang_id' => 'required|exists:barang,id',
      'd.*.level_satuan' => 'required',
      'd.*.harga_lama' => 'required|max:10000000000',
      'd.*.harga_barang' => 'required|max:10000000000',
      'd.*.keterangan' => 'nullable|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $hargaBarangModel = HargaBarangModel::find($request->input('id'));
    $hargaBarangModel->approve = false;
    $hargaBarangModel->user_id = $request->user()->id;
    $hargaBarangModel->save();

    $hargaBarangModel->d->each(fn ($d) => $d->delete());

    foreach ($request->input('d') as $d) {
      $barang = Barang::find($d['barang_id']);
      $constant = 1;

      for ($i = 1; $i <= $d['level_satuan']; $i++) {
        switch ($i) {
          case 1 :
            $constant = $constant;
            break;
          case 2 :
            $constant *= $barang->rasio_dua;
            break;
          case 3 :
            $constant *= $barang->rasio_tiga;
            break;
          case 4 :
            $constant *= $barang->rasio_empat;
            break;
          case 5 :
            $constant *= $barang->rasio_lima;
            break;
        }
      }

      $satuanId = null;
      switch ($d['level_satuan']) {
        case 1 :
          $satuanId = $barang->satuan_id;
          break;
        case 2 :
          $satuanId = $barang->satuan_dua_id;
          break;
        case 3 :
          $satuanId = $barang->satuan_tiga_id;
          break;
        case 4 :
          $satuanId = $barang->satuan_empat_id;
          break;
        case 5 :
          $satuanId = $barang->satuan_lima_id;
          break;
      }

      $detailModel = new HargaBarangD;
      $detailModel->harga_barang_id = $hargaBarangModel->id;
      $detailModel->barang_id = $d['barang_id'];
      $detailModel->level_satuan = $d['level_satuan'];
      $detailModel->qty_konversi = $d['qty'] * $constant;
      $detailModel->harga_barang = $d['harga_barang'];
      $detailModel->harga_lama = $d['harga_lama'];
      $detailModel->keterangan = $d['keterangan'] ?? '';
      $detailModel->save();
    }
  }

  public function amendAccepted(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'd'
    ), [
      'id' => 'required|exists:harga_barang,id',
      'd.*.barang_id' => 'required|exists:barang,id',
      'd.*.level_satuan' => 'required',
      'd.*.harga_lama' => 'required|max:10000000000',
      'd.*.harga_barang' => 'required|max:10000000000',
      'd.*.keterangan' => 'nullable|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $hargaBarangModel = HargaBarangModel::find($request->input('id'));
    $hargaBarangModel->approve = false;
    $hargaBarangModel->accepted = true;
    $hargaBarangModel->user_id = $request->user()->id;
    $hargaBarangModel->save();

    $hargaBarangModel->d->each(fn ($d) => $d->delete());

    foreach ($request->input('d') as $d) {
      $barang = Barang::find($d['barang_id']);
      $constant = 1;

      for ($i = 1; $i <= $d['level_satuan']; $i++) {
        switch ($i) {
          case 1 :
            $constant = $constant;
            break;
          case 2 :
            $constant *= $barang->rasio_dua;
            break;
          case 3 :
            $constant *= $barang->rasio_tiga;
            break;
          case 4 :
            $constant *= $barang->rasio_empat;
            break;
          case 5 :
            $constant *= $barang->rasio_lima;
            break;
        }
      }

      $satuanId = null;
      switch ($d['level_satuan']) {
        case 1 :
          $satuanId = $barang->satuan_id;
          break;
        case 2 :
          $satuanId = $barang->satuan_dua_id;
          break;
        case 3 :
          $satuanId = $barang->satuan_tiga_id;
          break;
        case 4 :
          $satuanId = $barang->satuan_empat_id;
          break;
        case 5 :
          $satuanId = $barang->satuan_lima_id;
          break;
      }

      $detailModel = new HargaBarangD;
      $detailModel->harga_barang_id = $hargaBarangModel->id;
      $detailModel->barang_id = $d['barang_id'];
      $detailModel->level_satuan = $d['level_satuan'];
      $detailModel->qty_konversi = $d['qty'] * $constant;
      $detailModel->harga_lama = $d['harga_lama'];
      $detailModel->harga_barang = $d['harga_barang'];
      $detailModel->accepted = true;
      $detailModel->keterangan = $d['keterangan'] ?? '';
      $detailModel->save();
    }
  }

  public function amendApprove(Request $request)
  {
    $v = Validator::make($request->only('id'), [
      'id' => 'required|exists:harga_barang,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $hargaBarangModel = HargaBarangModel::find($request->input('id'));
    $hargaBarangModel->approve = true;
    $hargaBarangModel->user_id = $request->user()->id;
    $hargaBarangModel->save();
  }

  public function destroy(Request $request)
  {
    $v = Validator::make($request->only('id'), [
      'id' => 'required|exists:harga_barang,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $hargaBarangModel = HargaBarangModel::find($request->input('id'));

    $hargaBarangModel->d->each(fn ($d) => $d->delete());

    $hargaBarangModel->user_id = $request->user()->id;
    $hargaBarangModel->save();
    $hargaBarangModel->delete();
  }
}