<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\FormLaporanCabang as FormLaporanCabangModel;
use App\Http\Models\FileLaporanCabang;
use App\Http\Models\Cabang;

class FormLaporanCabang extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(FormLaporanCabangModel::orderBy('waktu_mulai', 'DESC')->get())
      ->response(200);
  }

  public function branch(Request $request)
  {
    return $this
      ->data(FormLaporanCabangModel::where('cabang_id', $request->query('cabang_id'))
        ->orderBy('waktu_form', 'DESC')
        ->get()
      )
      ->response(200);
  }

  public function date(Request $request)
  {
    return $this
      ->data(FormLaporanCabangModel::with('cabang')
        ->whereDate('waktu_form', $request->query('tanggal_form'))
        ->whereIn('cabang_id',
          $request->user()->role->akses_semua_cabang
            ? Cabang::all()->modelKeys()
            : $request->user()->user_cabang()->pluck('cabang_id')->toArray()
        )
        ->orderBy('waktu_form', 'DESC')
        ->get()
      )
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if (is_null(FormLaporanCabangModel::with(['form_pemberian_tugas_cabang'])->find($id))) return $this->response(404);

    return $this->data(FormLaporanCabangModel::find($id))->response(200);
  }

  public function getFile(Request $request, $id) // WIP
  {
    if (is_null(FormLaporanCabangModel::find($id))) return $this->response(404);

    $model = FormLaporanCabangModel::find($id);
    $file = public_path() . '/' . $model->file_laporan_cabang[0]->dir;

    return response()->download($file);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'keterangan',
      'cabang_id',
      'file'
    ), [
      'keterangan' => 'required|max:200',
      'cabang_id' => 'required|exists:cabang,id',
      'file' => 'required'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new FormLaporanCabangModel;
    $model->keterangan = $request->input('keterangan');
    $model->cabang_id = $request->input('cabang_id');
    $model->waktu_form = date('Y-m-d H:i:s');
    $model->user_id = $request->user()->id;
    $model->save();

    $file = $request->file('file');
    $newFileName = uniqid() . uniqid() . '-' . uniqid() . uniqid() . '.' . $file->getClientOriginalExtension();
    $file->move('file', $newFileName);
    $fileLaporanCabangModel = new FileLaporanCabang;
    $fileLaporanCabangModel->form_laporan_cabang_id = $model->id;
    $fileLaporanCabangModel->dir = 'file/' . $newFileName;
    $fileLaporanCabangModel->save();
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'keterangan',
      'cabang_id',
    ), [
      'id' => 'required|exists:form_laporan_cabang,id',
      'keterangan' => 'required|max:200',
      'cabang_id' => 'required|exists:cabang,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = FormLaporanCabangModel::find($request->input('id'));
    $model->keterangan = $request->input('keterangan');
    $model->cabang_id = $request->input('cabang_id');
    $model->waktu_form = date('Y-m-d H:i:s');
    $model->user_id = $request->user()->id;
    $model->save();
  }

  public function destroy(Request $request)
  {
    $v = Validator::make($request->only(
      'id'
    ), [
      'id' => 'required|exists:form_pemberian_tugas,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = FormLaporanCabangModel::find($request->input('id'));
    $model->user_id = $request->user()->id;
    $model->save();
    $model->delete();
  }
}