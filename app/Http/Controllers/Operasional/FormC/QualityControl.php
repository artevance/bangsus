<?php

namespace App\Http\Controllers\Operasional\FormC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\FormQualityControl as FormQualityControlModel;
use App\Http\Models\FormQualityControlD as FormQualityControlDModel;
use App\Http\Models\QualityControl as QualityControlModel;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\Gambar as GambarModel;

class QualityControl extends Controller
{
  public function index(Request $request)
  {
    switch ($request->user()->role->role_code) {
      case 'admin' :
        $query = [
          'cabang_id' => $request->query('cabang_id', 1),
          'tanggal_form' => $request->query('tanggal_form', date('Y-m-d')),
          'quality_control_id' => $request->query('quality_control_id', 1)
        ];
      break;
      case 'leader' :
        $query = [
          'cabang_id' => $request->user()->tugas_karyawan->cabang_id,
          'tanggal_form' => date('Y-m-d'),
          'quality_control_id' => $request->query('quality_control_id', 1)
        ];
      break;
      default :
    }

    $this->title('Form C2 | BangsusSys')
      ->role($request->user()->role->role_code)
      ->query($query);
    return view('operasional.form_c.quality_control.wrapper', 
      $this->passParams([
        'cabangs' => CabangModel::all(),
        'qualityControls' => QualityControlModel::all(),
        'qualityControl' => QualityControlModel::find($query['quality_control_id'])
      ]
    ));
  }

  public function detail(FormQualityControlModel $formQualityControl, Request $request)
  {
    dd($formQualityControl->d);
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_quality_control,id'
    ]);

    return ['data' => FormQualityControlModel::with([
                        'd',
                          'd.opsi_parameter_quality_control',
                          'd.opsi_parameter_quality_control.parameter_quality_control',
                        'tugas_karyawan', 
                          'tugas_karyawan.cabang',
                            'tugas_karyawan.cabang.tipe_cabang',
                          'tugas_karyawan.divisi',
                          'tugas_karyawan.jabatan',
                          'tugas_karyawan.karyawan',
                            'tugas_karyawan.karyawan.golongan_darah',
                            'tugas_karyawan.karyawan.jenis_kelamin',
                        'quality_control',
                        'user'
                      ])
                      ->find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' => FormQualityControlModel::with([])
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
      'data' => FormQualityControlModel::with([
                  'd',
                    'd.opsi_parameter_quality_control',
                      'd.opsi_parameter_quality_control.parameter_quality_control',
                  'tugas_karyawan', 
                    'tugas_karyawan.cabang',
                      'tugas_karyawan.cabang.tipe_cabang',
                    'tugas_karyawan.divisi',
                    'tugas_karyawan.jabatan',
                    'tugas_karyawan.karyawan',
                      'tugas_karyawan.karyawan.golongan_darah',
                      'tugas_karyawan.karyawan.jenis_kelamin',
                  'quality_control',
                  'user'
                ])
                ->whereHas('tugas_karyawan', function ($q) use ($request) {
                    $q->where('cabang_id', '=', $request->input('cabang_id'));
                  })
                ->whereHas('quality_control', function ($q) use ($request) {
                    $q->where('quality_control_id', '=', $request->input('quality_control_id'));
                  })
                ->where('tanggal_form', $request->input('tanggal_form'))
                ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'quality_control_id' => 'required|exists:quality_control,id',
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id',
      'opsi_parameter_quality_control_id.*' => 'required|exists:opsi_parameter_quality_control,id'
    ]);

    $formQualityControlModel = new FormQualityControlModel;
    $formQualityControlModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formQualityControlModel->tanggal_form = $request->input('tanggal_form');
    $formQualityControlModel->jam = $request->input('jam');
    $formQualityControlModel->quality_control_id = $request->input('quality_control_id');
    $formQualityControlModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formQualityControlModel->user_id = $request->input('user_id');
    $formQualityControlModel->save();

    foreach ($request->input('opsi_parameter_quality_control_id') as $opsi_parameter_quality_control_id) {
      $formQualityControlDModel = new FormQualityControlDModel;
      $formQualityControlDModel->form_quality_control_id = $formQualityControlModel->id;
      $formQualityControlDModel->opsi_parameter_quality_control_id = $opsi_parameter_quality_control_id;
      $formQualityControlDModel->user_id = $request->input('user_id');
      $formQualityControlDModel->save();
    }
  }

  public function put(Request $request)
  {
    $request->validate([
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'quality_control_id' => 'required|exists:quality_control,id',
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id',
      'form_quality_control_d_id.*' => 'required|exists:form_quality_control_d,id',
      'opsi_parameter_quality_control_id.*' => 'required|exists:opsi_parameter_quality_control,id'
    ]);

    $formQualityControlModel = FormQualityControlModel::find($request->input('id'));
    $formQualityControlModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formQualityControlModel->tanggal_form = $request->input('tanggal_form');
    $formQualityControlModel->jam = $request->input('jam');
    $formQualityControlModel->quality_control_id = $request->input('quality_control_id');
    $formQualityControlModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formQualityControlModel->user_id = $request->input('user_id');
    $formQualityControlModel->save();

    foreach ($request->input('opsi_parameter_quality_control_id') as $i => $opsi_parameter_quality_control_id) {
      $formQualityControlDModel = FormQualityControlDModel::find($request->input('form_quality_control_d_id')[$i]);
      $formQualityControlDModel->form_quality_control_id = $formQualityControlModel->id;
      $formQualityControlDModel->opsi_parameter_quality_control_id = $opsi_parameter_quality_control_id;
      $formQualityControlDModel->user_id = $request->input('user_id');
      $formQualityControlDModel->save();
    }
  }

  public function delete(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_quality_control,id',
      'user_id' => 'required|exists:user,id'
    ]);

    $formQualityControlDModel = FormQualityControlDModel::where('form_quality_control_id', $request->input('id'));
    $formQualityControlDModel->delete();

    $formQualityControlModel = FormQualityControlModel::find($request->input('id'));
    $formQualityControlModel->user_id = $request->input('user_id');
    $formQualityControlModel->save();
    $formQualityControlModel->delete();
  }
}