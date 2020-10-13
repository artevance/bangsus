<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\FormPemberianTugas as FormPemberianTugasModel;
use App\Http\Models\FormPemberianTugasCabang;
use App\Http\Models\Cabang;

class FormPemberianTugas extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(FormPemberianTugasModel::with(['form_pemberian_tugas_cabang'])->orderBy('waktu_mulai', 'DESC')->get())
      ->response(200);
  }

  public function activeBranch(Request $request)
  {
    return $this
      ->data(FormPemberianTugasModel::with(['form_pemberian_tugas_cabang'])
        ->whereHas('form_pemberian_tugas_cabang', function ($q) use ($request) {
          $q->where('cabang_id', $request->query('cabang_id'));
        })
        ->whereDoesntHave('form_pengumpulan_tugas', function ($q) use ($request) {
          $q->where('cabang_id', $request->query('cabang_id'));
        })
        ->orderBy('waktu_mulai', 'DESC')
        ->get()
      )
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if (is_null(FormPemberianTugasModel::with(['form_pemberian_tugas_cabang'])->find($id))) return $this->response(404);

    return $this->data(FormPemberianTugasModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'judul_tugas',
      'keterangan',
      'waktu_mulai',
      'waktu_deadline',
      'semua_cabang',
      'cabang_id',
    ), [
      'judul_tugas' => 'required|max:200',
      'keterangan' => 'required|max:200',
      'waktu_mulai' => 'required',
      'waktu_deadline' => 'required',
      'semua_cabang' => 'nullable|boolean',
      'cabang_id' => 'nullable',
      'cabang_id.*' => 'required|exists:cabang,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new FormPemberianTugasModel;
    $model->judul_tugas = $request->input('judul_tugas');
    $model->keterangan = $request->input('keterangan');
    $model->waktu_mulai = $request->input('waktu_mulai');
    $model->waktu_deadline = $request->input('waktu_deadline');
    $model->user_id = $request->user()->id;
    $model->save();

    $cabangs = $request->boolean('semua_cabang')
      ? Cabang::all()->pluck('id')->toArray()
      : $request->input('cabang_id');
    foreach ($cabangs as $cabang_id) {
      $formPemberianTugasCabangModel = new FormPemberianTugasCabang;
      $formPemberianTugasCabangModel->form_pemberian_tugas_id = $model->id;
      $formPemberianTugasCabangModel->cabang_id = $cabang_id;
      $formPemberianTugasCabangModel->save();
    }
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'judul_tugas',
      'keterangan',
      'waktu_mulai',
      'waktu_deadline',
      'semua_cabang',
      'cabang_id',
    ), [
      'id' => 'required|exists:form_pemberian_tugas,id',
      'judul_tugas' => 'required|max:200',
      'keterangan' => 'required|max:200',
      'waktu_mulai' => 'required',
      'waktu_deadline' => 'required',
      'semua_cabang' => 'nullable|nullable',
      'cabang_id' => 'required',
      'cabang_id.*' => 'required|exists:cabang,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = FormPemberianTugasModel::find($request->input('id'));
    $model->judul_tugas = $request->input('judul_tugas');
    $model->keterangan = $request->input('keterangan');
    $model->waktu_mulai = $request->input('waktu_mulai');
    $model->waktu_deadline = $request->input('waktu_deadline');
    $model->user_id = $request->user()->id;
    $model->save();

    FormPemberianTugasCabang::where('form_pemberian_tugas_id', $model->id)->get()->delete();
    $cabangs = $request->boolean('semua_cabang')
      ? Cabang::all()->pluck('id')->toArray()
      : $request->input('cabang');
    foreach ($cabangs as $cabang_id) {
      $formPemberianTugasCabangModel = new FormPemberianTugasCabang;
      $formPemberianTugasCabangModel->form_pemberian_tugas_id = $model->id;
      $formPemberianTugasCabangModel->cabang_id = $cabang_id;
      $formPemberianTugasCabangModel->save();
    }
  }

  public function destroy(Request $request)
  {
    $v = Validator::make($request->only(
      'id'
    ), [
      'id' => 'required|exists:form_pemberian_tugas,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = FormPemberianTugasModel::find($request->input('id'));
    $model->user_id = $request->user()->id;
    $model->save();

    FormPemberianTugasCabang::where('form_pemberian_tugas_id', $model->id)->get()->delete();

    $model->delete();
  }
}