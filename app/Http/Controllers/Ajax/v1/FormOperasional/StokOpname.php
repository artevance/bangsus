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
use App\Http\Models\FailedJob;

use Intervention\Image\Facades\Image;

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
      'd',
      'd.barang',
      'd.barang.satuan',
      'd.barang.satuan_dua',
      'd.barang.satuan_tiga',
      'd.barang.satuan_empat',
      'd.barang.satuan_lima',
    ])->find($id))->response(200);
  }

  public function daily(Request $request)
  {
    $query = [
      'tanggal_form' => $request->input('tanggal_form', date('Y-m-d'))
    ];

    return $this
      ->data(
        StokOpnameModel::with([
          'cabang',
          'tipe_stok_opname',
          'd'
        ])
        ->where('tanggal_form', $query['tanggal_form'])
        ->orderBy('jam')
        ->get()
      )->response(200);
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
          'tipe_stok_opname',
          'd'
        ])
        ->where('tanggal_form', $query['tanggal_form'])
        ->byCabang($query['cabang_id'])
        ->orderBy('jam')
        ->get()
      )->response(200);
  }

  public function dailyBranchStokOpnameType(Request $request)
  {
    $query = [
      'cabang_id' => $request->input('cabang_id', Cabang::first()->id),
      'tanggal_form' => $request->input('tanggal_form', date('Y-m-d')),
      'tipe_stok_opname_id' => $request->input('tipe_stok_opname_id'),
    ];

    return $this
      ->data(
        StokOpnameModel::with([
          'cabang',
          'd'
        ])
        ->where('tanggal_form', $query['tanggal_form'])
        ->where('tipe_stok_opname_id', $query['tipe_stok_opname_id'])
        ->byCabang($query['cabang_id'])
        ->orderBy('jam')
        ->get()
      )->response(200);
  }

  public function image(Request $request, $id)
  {
    $return = response()->noContent();
    try {
      $return = Image::make(StokOpnameD::find($id)->dir_gambar)->response('jpeg');
    } catch (\Exception $e) {

    }

    return $return;
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'tanggal_opname',
      'jam_opname',
      'cabang_id',
      'supplier_id',
      'tipe_stok_opname_id',
      'd'
    ), [
      'cabang_id' => 'required|exists:cabang,id',
      'tipe_stok_opname_id' => 'required|exists:tipe_stok_opname,id',
      'd.*.barang_id' => 'required|exists:barang,id',
      'd.*.qty' => 'required|numeric|max:10000000000',
      'd.*.level_satuan' => 'required',
      'd.*.harga_barang' => 'required|max:10000000000',
      'd.*.keterangan' => 'nullable|max:200',
    ]);
    if ($v->fails()) {
      $failedJob = new FailedJob;
      $failedJob->payload = $v->errors();
      $failedJob->save();
      return $this->errors($v->errors())->response(422);
    };

    $so = StokOpnameModel::where(
      'cabang_id', $request->input('cabang_id')
    )
      ->whereMonth('tanggal_form', date('m'))
      ->whereYear('tanggal_form', date('Y'))
      ->orderBy('kode', 'desc')
      ->first();

    if (is_null($so)) {
      $inc = 1;
    } else {
      $inc = ((int)substr($so->kode, 13)) + 1;
    }

    $maxCodeLength = 4;
    $incLength = strlen((string) $inc);
    $zerosCount = $maxCodeLength - $incLength;

    $newInc = '';
    for ($i = 1; $i <= $zerosCount; $i++) {
      $newInc .= '0';
    }
    $newInc .= (string) $inc;

    $inc = $newInc;

    $kode = 'SOP-'.Cabang::find($request->input('cabang_id'))->kdvb.'-'.date('Ym').$inc;

    $stokOpnameModel = new StokOpnameModel;
    $stokOpnameModel->kode = $kode;
    $stokOpnameModel->tanggal_form = date('Y-m-d');
    $stokOpnameModel->jam = date('H:i:s');
    $stokOpnameModel->tanggal_opname = date('Y-m-d',
      strtotime($request->input('tanggal_opname', date('Y-m-d')))
    );
    $stokOpnameModel->jam_opname = date('H:i:s',
      strtotime($request->input('jam_opname', date('H:i:s')))
    );
    $stokOpnameModel->cabang_id = $request->input('cabang_id');
    $stokOpnameModel->tipe_stok_opname_id = $request->input('tipe_stok_opname_id');
    $stokOpnameModel->approve = false;
    $stokOpnameModel->user_id = $request->user()->id;
    $stokOpnameModel->save();

    foreach ($request->input('d') as $d) {
      if (isset($d['gambar'])) {
        $dir = public_path('img/opname/' . uniqid() . uniqid() . uniqid() . '.jpg');
        Image::make(file_get_contents($d['gambar']))->save($dir);
      }

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
      $detailModel->dir_gambar = $dir ?? '';
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
    ]);
    if ($v->fails()) {
      $failedJob = new FailedJob;
      $failedJob->payload = $v->errors();
      $failedJob->save();
      return $this->errors($v->errors())->response(422);
    };

    $stokOpnameModel = StokOpnameModel::find($request->input('id'));
    $stokOpnameModel->approve = false;
    $stokOpnameModel->user_id = $request->user()->id;
    $stokOpnameModel->save();

    $stokOpnameModel->d->each(
      function ($d) {
        $d->delete();
      }
    );

    foreach ($request->input('d') as $d) {
      if (isset($d['gambar'])) {
        $dir = public_path('img/opname/' . uniqid() . uniqid() . uniqid() . '.jpg');
        Image::make(file_get_contents($d['gambar']))->save($dir);
      }

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
      $detailModel->dir_gambar = $dir ?? $detailModel->dir;
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
        $d->delete();
      }
    );

    $stokOpnameModel->user_id = $request->user()->id;
    $stokOpnameModel->save();
    $stokOpnameModel->delete();
  }
}