<?php

namespace App\Http\Controllers\Ajax\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\TugasKaryawan as TugasKaryawanModel;

class TugasKaryawan extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(TugasKaryawanModel::with(['karyawan', 'cabang', 'divisi', 'jabatan'])->get())
      ->response(200);
  }

  public function parent(Request $request, $id)
  {
    return $this
      ->data(
        TugasKaryawanModel::with(['karyawan', 'cabang', 'divisi', 'jabatan'])
          ->where('karyawan_id', $id)
          ->get()
        )
      ->response(200);
  }

  public function branch(Request $request)
  {
    $query = [
      'cabang_id' => $request->query('cabang_id'),
      'tanggal_penugasan' => $request->query('tanggal_penugasan')
    ];

    return $this
      ->data(
        TugasKaryawanModel::with(['karyawan', 'cabang', 'divisi', 'jabatan'])
          ->where('cabang_id', $query['cabang_id'])
          ->where(function ($q) use ($query) {
            $q->whereDate('tanggal_mulai', '<=', $query['tanggal_penugasan'])
              ->where(function ($q) use ($query) {
                $q->whereDate('tanggal_selesai', '>=', $query['tanggal_penugasan'])
                  ->orWhere('tanggal_selesai', null);
              });
          })
          ->get()
        )
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! TugasKaryawanModel::where($id)->exists()) return $this->response(404);

    return $this
      ->data(
        TugasKaryawanModel::with(['karyawan', 'cabang', 'divisi', 'jabatan'])
          ->find($id)
        )
      ->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'cabang_id',
      'divisi_id',
      'jabatan_id',
      'karyawan_id',
      'tanggal_mulai',
      'tanggal_selesai',
      'no_finger'
    ), [
      'cabang_id' => 'required|exists:cabang,id',
      'divisi_id' => 'required|exists:divisi,id',
      'jabatan_id' => 'required|exists:jabatan,id',
      'karyawan_id' => 'required|exists:karyawan,id',
      'tanggal_mulai' => 'required|date',
      'tanggal_selesai' => 'nullable|date',
      'no_finger' => 'nullable|integer'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new TugasKaryawanModel;
    $model->cabang_id = $request->input('cabang_id');
    $model->divisi_id = $request->input('divisi_id');
    $model->jabatan_id = $request->input('jabatan_id');
    $model->karyawan_id = $request->input('karyawan_id');
    $model->tanggal_mulai = $request->input('tanggal_mulai');
    $model->tanggal_selesai = $request->input('tanggal_selesai');
    $model->no_finger = $request->input('no_finger');
    $model->save();
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'cabang_id',
      'divisi_id',
      'jabatan_id',
      'tanggal_mulai',
      'tanggal_selesai',
      'no_finger'
    ), [
      'id' => 'required|exists:tugas_karyawan,id',
      'cabang_id' => 'required|exists:cabang,id',
      'divisi_id' => 'required|exists:divisi,id',
      'jabatan_id' => 'required|exists:jabatan,id',
      'tanggal_mulai' => 'required|date',
      'tanggal_selesai' => 'nullable|date',
      'no_finger' => 'nullable|integer'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = TugasKaryawanModel::find($request->input('id'));
    $model->cabang_id = $request->input('cabang_id');
    $model->divisi_id = $request->input('divisi_id');
    $model->jabatan_id = $request->input('jabatan_id');
    $model->tanggal_mulai = $request->input('tanggal_mulai');
    $model->tanggal_selesai = $request->input('tanggal_selesai');
    $model->no_finger = $request->input('no_finger');
    $model->save();
  }
}