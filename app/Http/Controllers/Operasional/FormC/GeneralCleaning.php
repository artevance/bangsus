<?php

namespace App\Http\Controllers\Operasional\FormC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\FormGeneralCleaning as FormGeneralCleaningModel;
use App\Http\Models\AreaGeneralCleaning as AreaGeneralCleaningModel;
use App\Http\Models\KegiatanGeneralCleaning as KegiatanGeneralCleaningModel;
use App\Http\Models\Cabang as CabangModel;

class GeneralCleaning extends Controller
{
  public function index(Request $request)
  {
    switch ($request->user()->role->role_code) {
      case 'admin' :
        $query = [
          'cabang_id' => $request->query('cabang_id', 1),
          'tanggal_form' => $request->query('tanggal_form', date('Y-m-d'))
        ];
      break;
      case 'leader' :
        $query = [
          'cabang_id' => $request->user()->tugas_karyawan->cabang_id,
          'tanggal_form' => date('Y-m-d')
        ];
      break;
      default :
    }

    $this->title('Form C5 | BangsusSys')
      ->role($request->user()->role->role_code)
      ->query($query);
    return view('operasional.form_c.general_cleaning.wrapper', 
      $this->passParams([
        'cabangs' => CabangModel::all(),
        'areaGeneralCleanings' => AreaGeneralCleaningModel::all()
      ]
    ));
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_goreng,id'
    ]);

    return ['data' => FormGeneralCleaningModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' =>
        FormGeneralCleaningModel::with([])
          ->get()
    ];
  }

  public function cabangHarian(Request $request)
  {
    $request->validate([
      'cabang_id' => 'required|exists:cabang,id',
      'tanggal_form' => 'required|date'
    ]);

    return [
      'data' => 
        KegiatanGeneralCleaningModel::with([
            'form_general_cleaning' => function ($q) use ($request) {
              $q->with(['tugas_karyawan', 'tugas_karyawan.karyawan'])
                ->whereHas('tugas_karyawan', function ($q) use ($request) {
                  $q->where('cabang_id', $request->input('cabang_id'));
                })
                ->where('tanggal_form', $request->input('tanggal_form'));
            }
          ])
          ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'kegiatan_general_cleaning_id' => 'required|exists:kegiatan_general_cleaning,id',
      'skor' => 'required|numeric',
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id'
    ]);

    $formGeneralCleaningModel = new FormGeneralCleaningModel;
    $formGeneralCleaningModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formGeneralCleaningModel->tanggal_form = $request->input('tanggal_form');
    $formGeneralCleaningModel->jam = $request->input('jam');
    $formGeneralCleaningModel->kegiatan_general_cleaning_id = $request->input('kegiatan_general_cleaning_id');
    $formGeneralCleaningModel->skor = $request->input('skor');
    $formGeneralCleaningModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formGeneralCleaningModel->user_id = $request->input('user_id');
    $formGeneralCleaningModel->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_goreng,id',
      'tugas_karyawan_id' => 'nullable|exists:tugas_karyawan,id',
      'tanggal_form' => 'nullable|date_format:Y-m-d',
      'jam' => 'nullable',
      'kegiatan_general_cleaning_id' => 'nullable|exists:kegiatan_general_cleaning,id',
      'skor' => 'nullable|numeric',
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id',
    ]);

    $formGeneralCleaningModel = FormGeneralCleaningModel::find($request->input('id'));
    if ($request->has('tugas_karyawan_id')) $formGeneralCleaningModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    if ($request->has('tanggal_form')) $formGeneralCleaningModel->tanggal_form = $request->input('tanggal_form');
    if ($request->has('jam')) $formGeneralCleaningModel->jam = $request->input('jam');
    if ($request->has('kegiatan_general_cleaning_id')) $formGeneralCleaningModel->kegiatan_general_cleaning_id = $request->input('kegiatan_general_cleaning_id');
    if ($request->has('skor')) $formGeneralCleaningModel->skor = $request->input('skor');
    if ($request->has('keterangan')) $formGeneralCleaningModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formGeneralCleaningModel->user_id = $request->input('user_id');
    $formGeneralCleaningModel->save();
  }

  public function delete(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_goreng,id',
      'user_id' => 'required|exists:user,id'
    ]);

    $formGeneralCleaningModel = FormGeneralCleaningModel::find($request->input('id'));
    $formGeneralCleaningModel->user_id = $request->input('user_id');
    $formGeneralCleaningModel->save();
    $formGeneralCleaningModel->delete();
  }
}