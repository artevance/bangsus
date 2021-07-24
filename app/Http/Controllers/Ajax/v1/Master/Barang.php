<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\Barang as BarangModel;
use App\Http\Models\OpnameBarangTipeCabang as OpnameBarangTipeCabangModel;
use App\Http\Models\BarangTipeStokOpname as BarangTipeStokOpnameModel;

class Barang extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(BarangModel::with(['satuan', 'satuan_dua', 'satuan_tiga', 'satuan_empat', 'satuan_lima'])
          ->where('kode_barang', 'like', '%' . $request->input('q') . '%')
          ->orWhere('nama_barang', 'like', '%' . $request->input('q') . '%')
          ->get()
        )
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! BarangModel::find($id)->exists()) return $this->response(404);

    return $this->data(BarangModel::with(['satuan', 'satuan_dua', 'satuan_tiga', 'satuan_empat', 'satuan_lima', 'opname_barang_tipe_cabang', 'barang_tipe_stok_opname'])->find($id))->response(200);
  }

  public function getOpname(Request $request, $tipeCabangId, $tipeStokOpnameId)
  {
    return $this
      ->data(BarangModel::with(['satuan', 'satuan_dua', 'satuan_tiga', 'satuan_empat', 'satuan_lima'])
          ->where(function ($query) use ($request) {
            $query->where('kode_barang', 'like', '%' . $request->input('q') . '%')
              ->orWhere('nama_barang', 'like', '%' . $request->input('q') . '%');
          })
          ->where(function ($query) use ($tipeCabangId) {
            $query->whereHas('opname_barang_tipe_cabang', function ($query) use ($tipeCabangId) {
              $query->where('tipe_cabang_id', $tipeCabangId);
            })->orWhere('semua_tipe_cabang', true);
          })
          ->where(function ($query) use ($tipeStokOpnameId) {
            $query->whereHas('barang_tipe_stok_opname', function ($query) use ($tipeStokOpnameId) {
              $query->where('tipe_stok_opname_id', $tipeStokOpnameId);
            });
          })
          ->get()
        )
      ->response(200);
  }

  public function getPurchaseOrder(Request $request)
  {
    return $this
      ->data(BarangModel::with(['satuan', 'satuan_dua', 'satuan_tiga', 'satuan_empat', 'satuan_lima'])
          ->where(function ($query) use ($request) {
            $query->where('kode_barang', 'like', '%' . $request->input('q') . '%')
              ->orWhere('nama_barang', 'like', '%' . $request->input('q') . '%');
          })
          ->where('purchase_order', true)
          ->get()
        )
      ->response(200);
  }

  public function getMutation(Request $request)
  {
    return $this
      ->data(BarangModel::with(['satuan', 'satuan_dua', 'satuan_tiga', 'satuan_empat', 'satuan_lima'])
          ->where(function ($query) use ($request) {
            $query->where('kode_barang', 'like', '%' . $request->input('q') . '%')
              ->orWhere('nama_barang', 'like', '%' . $request->input('q') . '%');
          })
          ->where('mutation', true)
          ->get()
        )
      ->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'kode_barang',
      'nama_barang',
      'satuan_id',
      'satuan_dua_id',
      'rasio_dua',
      'satuan_tiga_id',
      'rasio_tiga',
      'satuan_empat_id',
      'rasio_empat',
      'satuan_lima_id',
      'rasio_lima',
      'semua_tipe_cabang',
      'tipe_cabang_id',
      'semua_tipe_stok_opname',
      'tipe_stok_opname_id',
      'mutation',
      'purchase_order',
    ), [
      'kode_barang' => 'required|max:200',
      'nama_barang' => 'required|max:200',
      'satuan_id' => 'required|exists:satuan,id',
      'satuan_dua_id' => 'nullable|exists:satuan,id',
      'rasio_dua' => 'required_with:satuan_dua_id|numeric',
      'satuan_tiga_id' => 'nullable|exists:satuan,id',
      'rasio_tiga' => 'required_with:satuan_tiga_id|numeric',
      'satuan_empat_id' => 'nullable|exists:satuan,id',
      'rasio_empat' => 'required_with:satuan_empat_id|numeric',
      'satuan_lima_id' => 'nullable|exists:satuan,id',
      'rasio_lima' => 'required_with:satuan_lima_id|numeric',
      'semua_tipe_cabang' => 'required|boolean',
      'tipe_cabang_id' => 'required|array',
      'tipe_cabang_id.*' => 'required|exists:tipe_cabang,id',
      'semua_tipe_stok_opname' => 'required|boolean',
      'tipe_stok_opname_id' => 'required|array',
      'tipe_stok_opname_id.*' => 'required|exists:tipe_stok_opname,id',
      'mutation' => 'required|boolean',
      'purchase_order' => 'required|boolean',
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new BarangModel;
    $model->kode_barang = strtoupper($request->input('kode_barang'));
    $model->nama_barang = strtoupper($request->input('nama_barang'));
    $model->satuan_id = $request->input('satuan_id');
    $model->satuan_dua_id = $request->has('satuan_dua_id') ? $request->input('satuan_dua_id') : null;
    $model->rasio_dua = $request->has('satuan_dua_id') ? $request->input('rasio_dua') : null;
    $model->satuan_tiga_id = $request->has('satuan_tiga_id') ? $request->input('satuan_tiga_id') : null;
    $model->rasio_tiga = $request->has('satuan_tiga_id') ? $request->input('rasio_tiga') : null;
    $model->satuan_empat_id = $request->has('satuan_empat_id') ? $request->input('satuan_empat_id') : null;
    $model->rasio_empat = $request->has('satuan_empat_id') ? $request->input('rasio_empat') : null;
    $model->satuan_lima_id = $request->has('satuan_lima_id') ? $request->input('satuan_lima_id') : null;
    $model->rasio_lima = $request->has('satuan_lima_id') ? $request->input('rasio_lima') : null;
    $model->semua_tipe_cabang = $request->boolean('semua_tipe_cabang');
    $model->semua_tipe_stok_opname = $request->boolean('semua_tipe_stok_opname');
    $model->mutation = $request->boolean('mutation');
    $model->purchase_order = $request->boolean('purchase_order');
    $model->save();

    if ( ! $model->semua_tipe_cabang) {
      foreach ($request->input('tipe_cabang_id') as $tipeCabangId) {
        $opnameBarangTipeCabangModel = new OpnameBarangTipeCabangModel;
        $opnameBarangTipeCabangModel->barang_id = $model->id;
        $opnameBarangTipeCabangModel->tipe_cabang_id = $tipeCabangId;
        $opnameBarangTipeCabangModel->save();
      }
    }

    if ( ! $model->semua_tipe_stok_opname) {
      foreach ($request->input('tipe_stok_opname_id') as $tipeStokOpnameId) {
        $opnameBarangTipeCabangModel = new BarangTipeStokOpnameModel;
        $opnameBarangTipeCabangModel->barang_id = $model->id;
        $opnameBarangTipeCabangModel->tipe_stok_opname_id = $tipeStokOpnameId;
        $opnameBarangTipeCabangModel->save();
      }
    }

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'kode_barang',
      'nama_barang',
      'satuan_id',
      'satuan_dua_id',
      'rasio_dua',
      'satuan_tiga_id',
      'rasio_tiga',
      'satuan_empat_id',
      'rasio_empat',
      'satuan_lima_id',
      'rasio_lima',
      'semua_tipe_cabang',
      'tipe_cabang_id',
      'semua_tipe_stok_opname',
      'tipe_stok_opname_id',
      'mutation',
      'purchase_order',
    ), [
      'id' => 'required|exists:barang,id',
      'kode_barang' => 'required|max:200',
      'nama_barang' => 'required|max:200',
      'satuan_id' => 'required|exists:satuan,id',
      'satuan_dua_id' => 'nullable|exists:satuan,id',
      'rasio_dua' => 'required_with:satuan_dua_id|numeric',
      'satuan_tiga_id' => 'nullable|exists:satuan,id',
      'rasio_tiga' => 'required_with:satuan_tiga_id|numeric',
      'satuan_empat_id' => 'nullable|exists:satuan,id',
      'rasio_empat' => 'required_with:satuan_empat_id|numeric',
      'satuan_lima_id' => 'nullable|exists:satuan,id',
      'rasio_lima' => 'required_with:satuan_lima_id|numeric',
      'semua_tipe_cabang' => 'required|boolean',
      'tipe_cabang_id' => 'required|array',
      'tipe_cabang_id.*' => 'required|exists:tipe_cabang,id',
      'semua_tipe_stok_opname' => 'required|boolean',
      'tipe_stok_opname_id' => 'required|array',
      'tipe_stok_opname_id.*' => 'required|exists:tipe_stok_opname,id',
      'mutation' => 'required|boolean',
      'purchase_order' => 'required|boolean',
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = BarangModel::find($request->input('id'));
    $model->kode_barang = strtoupper($request->input('kode_barang'));
    $model->nama_barang = strtoupper($request->input('nama_barang'));
    $model->satuan_id = $request->input('satuan_id');
    $model->satuan_dua_id = $request->has('satuan_dua_id') ? $request->input('satuan_dua_id') : null;
    $model->rasio_dua = $request->has('satuan_dua_id') ? $request->input('rasio_dua') : null;
    $model->satuan_tiga_id = $request->has('satuan_tiga_id') ? $request->input('satuan_tiga_id') : null;
    $model->rasio_tiga = $request->has('satuan_tiga_id') ? $request->input('rasio_tiga') : null;
    $model->satuan_empat_id = $request->has('satuan_empat_id') ? $request->input('satuan_empat_id') : null;
    $model->rasio_empat = $request->has('satuan_empat_id') ? $request->input('rasio_empat') : null;
    $model->satuan_lima_id = $request->has('satuan_lima_id') ? $request->input('satuan_lima_id') : null;
    $model->rasio_lima = $request->has('satuan_lima_id') ? $request->input('rasio_lima') : null;
    $model->semua_tipe_cabang = $request->boolean('semua_tipe_cabang');
    $model->semua_tipe_stok_opname = $request->boolean('semua_tipe_stok_opname');
    $model->mutation = $request->boolean('mutation');
    $model->purchase_order = $request->boolean('purchase_order');
    $model->save();

    if ($model->semua_tipe_cabang) {
      $model->opname_barang_tipe_cabang()
        ->each(function ($opnameBarangTipeCabangModel) {
          $opnameBarangTipeCabangModel->delete();
        });
    } else {
      $model->opname_barang_tipe_cabang()
        ->whereNotIn('tipe_cabang_id', $request->input('tipe_cabang_id'))
        ->each(function ($opnameBarangTipeCabangModel) {
          $opnameBarangTipeCabangModel->delete();
        });

      foreach ($request->input('tipe_cabang_id') as $tipeCabangId) {
        if ($model->opname_barang_tipe_cabang()->where('tipe_cabang_id', $tipeCabangId)->exists()) {
          continue;
        }

        $opnameBarangTipeCabangModel = new OpnameBarangTipeCabangModel;
        $opnameBarangTipeCabangModel->barang_id = $model->id;
        $opnameBarangTipeCabangModel->tipe_cabang_id = $tipeCabangId;
        $opnameBarangTipeCabangModel->save();
      }
    }

    if ($model->semua_tipe_stok_opname) {
      $model->barang_tipe_stok_opname()
        ->each(function ($barangTipeStokOpnameModel) {
          $barangTipeStokOpnameModel->delete();
        });
    } else {
      $model->barang_tipe_stok_opname()
        ->whereNotIn('tipe_stok_opname_id', $request->input('tipe_stok_opname_id'))
        ->each(function ($barangTipeStokOpnameModel) {
          $barangTipeStokOpnameModel->delete();
        });

      foreach ($request->input('tipe_stok_opname_id') as $tipeCabangId) {
        if ($model->barang_tipe_stok_opname()->where('tipe_stok_opname_id', $tipeCabangId)->exists()) {
          continue;
        }

        $barangTipeStokOpnameModel = new OpnameBarangTipeCabangModel;
        $barangTipeStokOpnameModel->barang_id = $model->id;
        $barangTipeStokOpnameModel->tipe_stok_opname_id = $tipeCabangId;
        $barangTipeStokOpnameModel->save();
      }
    }

    return $this->data(['update_id' => $model->id])->response(200);
  }
}