<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\StokOpname as StokOpnameModel;
use App\Http\Models\StokOpnameD;
use App\Http\Models\Barang;
use App\Http\Models\Cabang;
use App\Http\Models\Gambar;

class StokOpname extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(StokOpnameModel::with([
        'cabang',
        'd'
      ]))
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if (is_null(StokOpnameModel::find($id))) return $this->response(404);

    return $this->data(StokOpnameModel::with([
      'cabang',
      'd'
    ])->find($id))->response(200);
  }

  public function dailyBranch(Request $request)
  {
    $query = [
      'cabang_id' => $request->input('cabang_id', Cabang::first()->id),
      'tanggal_form' => $request->input('tanggal_form', date('Y-m-d'))
    ];

    return $this
      ->data(
        StokOpnameModel::with([
          'cabang',
          'd'
        ])
        ->where('tanggal_form', $query['tanggal_form'])
        ->byCabang($query['cabang_id'])
        ->orderBy('jam')
        ->get()
      )->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'cabang_id',
      'supplier_id',
      'd'
    ), [
      'cabang_id' => 'required|exists:cabang,id',
      'd.*.barang_id' => 'required|exists:barang,id',
      'd.*.qty' => 'required|numeric|max:10000000000',
      'd.*.level_satuan' => 'required',
      'd.*.harga_barang' => 'required|max:10000000000',
      'd.*.keterangan' => 'nullable|max:200',
      'd.*.gambar' => 'required'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $stokOpnameModel = new StokOpnameModel;
    $stokOpnameModel->tanggal_form = date('Y-m-d');
    $stokOpnameModel->jam = date('H:i:s');
    $stokOpnameModel->cabang_id = $request->input('cabang_id');
    $stokOpnameModel->approve = false;
    $stokOpnameModel->user_id = $request->user()->id;
    $stokOpnameModel->save();

    foreach ($request->input('d') as $d) {
      $gambarModel = new Gambar;
      $gambarModel->konten = base64_decode(str_replace(' ', '+', explode(',', $d['gambar'])[1]));
      $gambarModel->save();

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

      $detailModel = new StokOpnameD;
      $detailModel->stok_opname_id = $stokOpnameModel->id;
      $detailModel->gambar_id = $gambarModel->id;
      $detailModel->barang_id = $d['barang_id'];
      $detailModel->qty = $d['qty'];
      $detailModel->level_satuan = $d['level_satuan'];
      $detailModel->qty_konversi = $d['qty'] * $constant;
      $detailModel->harga_barang = $d['harga_barang'];
      $detailModel->keterangan = $d['keterangan'] ?? '';
      $detailModel->save();
    }
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'supplier_id',
      'd'
    ), [
      'id' => 'required|exists:stok_opname,id',
      'd.*.barang_id' => 'required|exists:barang,id',
      'd.*.qty' => 'required|numeric|max:10000000000',
      'd.*.level_satuan' => 'required',
      'd.*.harga_barang' => 'required|max:10000000000',
      'd.*.keterangan' => 'nullable|max:200',
      'd.*.gambar' => 'required'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $stokOpnameModel = StokOpnameModel::find($request->input('id'));
    $stokOpnameModel->approve = false;
    $stokOpnameModel->user_id = $request->user()->id;
    $stokOpnameModel->save();

    $stokOpnameModel->d->each(
      function ($d) {
        $gambarModel = $d->gambar;
        $gambarModel->konten = '';
        $gambarModel->save();
        $d->delete();
      }
    );

    foreach ($request->input('d') as $d) {
      $gambarModel = new Gambar;
      $gambarModel->konten = base64_decode(str_replace(' ', '+', explode(',', $d['gambar'])[1]));
      $gambarModel->save();

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

      $detailModel = new StokOpnameD;
      $detailModel->stok_opname_id = $stokOpnameModel->id;
      $detailModel->gambar_id = $gambarModel->id;
      $detailModel->barang_id = $d['barang_id'];
      $detailModel->qty = $d['qty'];
      $detailModel->level_satuan = $d['level_satuan'];
      $detailModel->qty_konversi = $d['qty'] * $constant;
      $detailModel->harga_barang = $d['harga_barang'];
      $detailModel->keterangan = $d['keterangan'] ?? '';
      $detailModel->save();
    }
  }

  public function amendApprove(Request $request)
  {
    $v = Validator::make($request->only('id'), [
      'id' => 'required|exists:stok_opname,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $stokOpnameModel = StokOpnameModel::find($request->input('id'));
    $stokOpnameModel->approve = true;
    $stokOpnameModel->user_id = $request->user()->id;
    $stokOpnameModel->save();
  }

  public function destroy(Request $request)
  {
    $v = Validator::make($request->only('id'), [
      'id' => 'required|exists:stok_opname,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $stokOpnameModel = StokOpnameModel::find($request->input('id'));

    $stokOpnameModel->d->each(
      function ($d) {
        $gambarModel = $d->gambar;
        $gambarModel->konten = '';
        $gambarModel->save();
        $d->delete();
      }
    );

    $stokOpnameModel->user_id = $request->user()->id;
    $stokOpnameModel->save();
    $stokOpnameModel->delete();
  }
}