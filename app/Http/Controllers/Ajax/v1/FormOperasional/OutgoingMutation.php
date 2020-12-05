<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\OutgoingMutation as OutgoingMutationModel;
use App\Http\Models\OutgoingMutationD;
use App\Http\Models\Barang;
use App\Http\Models\Cabang;
use App\Http\Models\Gambar;

use Intervention\Image\Facades\Image;

class OutgoingMutation extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(OutgoingMutationModel::with([
        'cabang',
        'd'
      ]))
      ->response(200);
  }

  public function forIncomingMutation(Request $request)
  {
    return $this
      ->data(
        OutgoingMutationModel::with([
          'cabang',
          'd'
        ])
          ->where([
            'cabang_id' => $request->query('cabang_id'),
            'cabang_tujuan_id' => $request->query('cabang_tujuan_id'),
          ])
          ->whereDoesntHave('incoming_mutation')
          ->get()
      )
      ->response(200);
  }

  public function forIncomingMutationUpdate(Request $request)
  {
    return $this
      ->data(
        OutgoingMutationModel::with([
          'cabang',
          'd'
        ])
          ->where([
            'cabang_id' => $request->query('cabang_id'),
            'cabang_tujuan_id' => $request->query('cabang_tujuan_id'),
          ])
          ->whereHas('incoming_mutation')
          ->get()
      )
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if (is_null(OutgoingMutationModel::find($id))) return $this->response(404);

    return $this->data(OutgoingMutationModel::with([
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
        OutgoingMutationModel::with([
          'cabang',
          'cabang_tujuan',
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
        OutgoingMutationModel::with([
          'cabang',
          'cabang_tujuan',
          'd'
        ])
        ->where('tanggal_form', $query['tanggal_form'])
        ->byCabang($query['cabang_id'])
        ->orderBy('jam')
        ->get()
      )->response(200);
  }

  public function image(Request $request, $id)
  {
    return Image::make(OutgoingMutationD::find($id)->dir_gambar)->response('jpeg');
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'cabang_id',
      'cabang_tujuan_id',
      'd'
    ), [
      'cabang_id' => 'required|exists:cabang,id',
      'cabang_tujuan_id' => 'required|exists:cabang,id',
      'd.*.barang_id' => 'required|exists:barang,id',
      'd.*.qty' => 'required|numeric|max:10000000000',
      'd.*.level_satuan' => 'required',
      'd.*.harga_barang' => 'required|max:10000000000',
      'd.*.keterangan' => 'nullable|max:200',
      'd.*.gambar' => 'required'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $outgoingMutationModel = new OutgoingMutationModel;
    $outgoingMutationModel->tanggal_form = date('Y-m-d');
    $outgoingMutationModel->jam = date('H:i:s');
    $outgoingMutationModel->cabang_id = $request->input('cabang_id');
    $outgoingMutationModel->cabang_tujuan_id = $request->input('cabang_tujuan_id');
    $outgoingMutationModel->approve = false;
    $outgoingMutationModel->user_id = $request->user()->id;
    $outgoingMutationModel->save();

    foreach ($request->input('d') as $d) {
      $dir = public_path('opname/' . uniqid() . uniqid() . uniqid() . '.jpg');
      Image::make(file_get_contents($d['gambar']))->save($dir);

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

      $detailModel = new OutgoingMutationD;
      $detailModel->outgoing_mutation_id = $outgoingMutationModel->id;
      $detailModel->dir_gambar = $dir;
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
      'd'
    ), [
      'id' => 'required|exists:outgoing_mutation,id',
      'd.*.barang_id' => 'required|exists:barang,id',
      'd.*.qty' => 'required|numeric|max:10000000000',
      'd.*.level_satuan' => 'required',
      'd.*.harga_barang' => 'required|max:10000000000',
      'd.*.keterangan' => 'nullable|max:200',
      'd.*.gambar' => 'required'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $outgoingMutationModel = OutgoingMutationModel::find($request->input('id'));
    $outgoingMutationModel->approve = false;
    $outgoingMutationModel->user_id = $request->user()->id;
    $outgoingMutationModel->save();

    $outgoingMutationModel->d->each(
      function ($d) {
        $d->delete();
      }
    );

    foreach ($request->input('d') as $d) {
      $dir = public_path('opname/' . uniqid() . uniqid() . uniqid() . '.jpg');
      Image::make(file_get_contents($d['gambar']))->save($dir);

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

      $detailModel = new OutgoingMutationD;
      $detailModel->outgoing_mutation_id = $outgoingMutationModel->id;
      $detailModel->dir_gambar = $dir;
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
      'id' => 'required|exists:outgoing_mutation,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $outgoingMutationModel = OutgoingMutationModel::find($request->input('id'));
    $outgoingMutationModel->approve = true;
    $outgoingMutationModel->user_id = $request->user()->id;
    $outgoingMutationModel->save();
  }

  public function destroy(Request $request)
  {
    $v = Validator::make($request->only('id'), [
      'id' => 'required|exists:outgoing_mutation,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $outgoingMutationModel = OutgoingMutationModel::find($request->input('id'));

    $outgoingMutationModel->d->each(
      function ($d) {
        $d->delete();
      }
    );

    $outgoingMutationModel->user_id = $request->user()->id;
    $outgoingMutationModel->save();
    $outgoingMutationModel->delete();
  }
}