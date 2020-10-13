<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\FormPengumpulanTugas as FormPengumpulanTugasModel;
use App\Http\Models\FileTugas;
use App\Http\Models\Cabang;

class FormPengumpulanTugas extends Controller
{
  public function index(Request $request) // WIP
  {
    return $this
      ->data(FormPengumpulanTugasModel::with(['form_pemberian_tugas_cabang'])->orderBy('waktu_mulai', 'DESC')->get())
      ->response(200);
  }

  public function activeBranch(Request $request) // WIP
  {
    return $this
      ->data(FormPengumpulanTugasModel::with(['form_pemberian_tugas_cabang'])
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

  public function get(Request $request, $id) // WIP
  {
    if (is_null(FormPengumpulanTugasModel::with(['form_pemberian_tugas_cabang'])->find($id))) return $this->response(404);

    return $this->data(FormPengumpulanTugasModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'form_pemberian_tugas_id',
      'cabang_id',
      'keterangan',
      'file',
    ), [
      'form_pemberian_tugas_id' => 'required|exists:form_pemberian_tugas,id',
      'cabang_id' => 'required|exists:cabang,id',
      'keterangan' => 'required|max:200',
      'file' => 'required'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new FormPengumpulanTugasModel;
    $model->form_pemberian_tugas_id = $request->input('form_pemberian_tugas_id');
    $model->keterangan = $request->input('keterangan');
    $model->cabang_id = $request->input('cabang_id');
    $model->user_id = $request->user()->id;
    $model->waktu_pengumpulan = date('Y-m-d H:i:s');
    $model->save();

    $file = $request->file('file');
    $newFileName = uniqid() . uniqid() . '-' . uniqid() . uniqid() . '.' . $file->getClientOriginalExtension();
    $file->move('file', $newFileName);
    $fileTugasModel = new FileTugas;
    $fileTugasModel->form_pengumpulan_tugas_id = $model->id;
    $fileTugasModel->dir = 'file/' . $newFileName;
    $fileTugasModel->save();
  }

  public function amend(Request $request) // WIP
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

    $model = FormPengumpulanTugasModel::find($request->input('id'));
    $model->judul_tugas = $request->input('judul_tugas');
    $model->keterangan = $request->input('keterangan');
    $model->waktu_mulai = $request->input('waktu_mulai');
    $model->waktu_deadline = $request->input('waktu_deadline');
    $model->user_id = $request->user()->id;
    $model->save();

    FormPengumpulanTugasCabang::where('form_pemberian_tugas_id', $model->id)->get()->delete();
    $cabangs = $request->boolean('semua_cabang')
      ? Cabang::all()->pluck('id')->toArray()
      : $request->input('cabang');
    foreach ($cabangs as $cabang_id) {
      $formPemberianTugasCabangModel = new FormPengumpulanTugasCabang;
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

    $model = FormPengumpulanTugasModel::find($request->input('id'));
    $model->user_id = $request->user()->id;
    $model->save();

    FormPengumpulanTugasCabang::where('form_pemberian_tugas_id', $model->id)->get()->delete();

    $model->delete();
  }
}