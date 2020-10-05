<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\KelompokFoto as KelompokFotoModel;
use App\Http\Models\DendaFoto;
use App\Http\Models\PengaturanKelompokFoto;

class KelompokFoto extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(KelompokFotoModel::with(['denda_foto', 'pengaturan_kelompok_foto'])->where('kelompok_foto', 'like', '%' . $request->input('q') . '%')->get())
      ->response(200);
  }

  public function fillable(Request $request)
  {
    return $this
      ->data(KelompokFotoModel::with(['denda_foto', 'pengaturan_kelompok_foto'])->where('kelompok_foto', 'like', '%' . $request->input('q') . '%')->where('master', false)->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! KelompokFotoModel::with(['denda_foto', 'pengaturan_kelompok_foto'])->find($id)->exists()) return $this->response(404);

    return $this->data(KelompokFotoModel::with(['denda_foto', 'pengaturan_kelompok_foto'])->find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'kelompok_foto',
      'denda_tidak_kirim',
      'nominal',
      'qty_minimum_form'
    ), [
      'kelompok_foto' => 'required|max:200',
      'denda_tidak_kirim' => 'required',
      'nominal' => 'required_if:denda_tidak_kirim,1,true|numeric',
      'qty_minimum_form' => 'required_if:denda_tidak_kirim,1,true|numeric'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new KelompokFotoModel;
    $model->kelompok_foto = strtoupper($request->input('kelompok_foto'));
    $model->master = 0;
    $model->denda_tidak_kirim = $request->boolean('denda_tidak_kirim');
    $model->save();

    if ($request->boolean('denda_tidak_kirim')) {
      $dendaFotoModel = new DendaFoto;
      $dendaFotoModel->kelompok_foto_id = $model->id;
      $dendaFotoModel->denda_foto = 'TIDAK KIRIM';
      $dendaFotoModel->nominal = $request->input('nominal');
      $dendaFotoModel->master = 1;
      $dendaFotoModel->save();

      $pengaturanKelompokFotoModel = new PengaturanKelompokFoto;
      $pengaturanKelompokFotoModel->kelompok_foto_id = $model->id;
      $pengaturanKelompokFotoModel->denda_foto_id = $dendaFotoModel->id;
      $pengaturanKelompokFotoModel->qty_minimum_form = $request->input('qty_minimum_form');
      $pengaturanKelompokFotoModel->save();
    }

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'kelompok_foto',
      'qty_minimum_form'
    ), [
      'id' => [
        'required',
        Rule::exists('kelompok_foto')->where(function ($q) {
          $q->where('master', false);
        })
      ],
      'kelompok_foto' => 'required|max:200',
      'qty_minimum_form' => 'nullable|numeric'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = KelompokFotoModel::find($request->input('id'));
    $model->kelompok_foto = strtoupper($request->input('kelompok_foto'));
    $model->save();

    if ($model->denda_tidak_kirim == 1) {
      $pengaturanKelompokFotoModel = PengaturanKelompokFoto::where('kelompok_foto_id', $request->input('id'))->first();
      $pengaturanKelompokFotoModel->qty_minimum_form = $request->input('qty_minimum_form');
      $pengaturanKelompokFotoModel->save();
    }

    return $this->data(['update_id' => $model->id])->response(200);
  }
}