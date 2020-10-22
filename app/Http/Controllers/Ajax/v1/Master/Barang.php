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

    return $this->data(BarangModel::with(['satuan', 'satuan_dua', 'satuan_tiga', 'satuan_empat', 'satuan_lima'])->find($id))->response(200);
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
      'rasio_lima'
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
    $model->save();

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
      'rasio_lima'
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
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}