<?php

namespace App\Http\Controllers\Operasional\FormC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\FormAtributKaryawan as FormAtributKaryawanModel;
use App\Http\Models\FormAtributKaryawanD as FormAtributKaryawanDModel;
use App\Http\Models\AtributKaryawan as AtributKaryawanModel;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\Gambar as GambarModel;

class AtributKaryawan extends Controller
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

    $this->title('Form C3 | BangsusSys')
      ->role($request->user()->role->role_code)
      ->query($query);
    return view('operasional.form_c.atribut_karyawan.wrapper', 
      $this->passParams([
        'cabangs' => CabangModel::all(),
        'atributKaryawans' => AtributKaryawanModel::all()
      ]
    ));
  }

  public function detail(FormAtributKaryawanModel $formAtributKaryawan, Request $request)
  {
    return redirect(url()->previous());
    // dd($formAtributKaryawan->d);
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_atribut_karyawan,id'
    ]);

    return ['data' => FormAtributKaryawanModel::with([
                        'd',
                          'd.parameter_atribut_karyawan',
                        'tugas_karyawan', 
                          'tugas_karyawan.cabang',
                            'tugas_karyawan.cabang.tipe_cabang',
                          'tugas_karyawan.divisi',
                          'tugas_karyawan.jabatan',
                          'tugas_karyawan.karyawan',
                            'tugas_karyawan.karyawan.golongan_darah',
                            'tugas_karyawan.karyawan.jenis_kelamin',
                        'aktivitas_karyawan',
                        'user'
                      ])
                      ->find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' => FormAtributKaryawanModel::with([])
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
      'data' => FormAtributKaryawanModel::with([
                  'd',
                    'd.parameter_atribut_karyawan',
                  'tugas_karyawan', 
                    'tugas_karyawan.cabang',
                      'tugas_karyawan.cabang.tipe_cabang',
                    'tugas_karyawan.divisi',
                    'tugas_karyawan.jabatan',
                    'tugas_karyawan.karyawan',
                      'tugas_karyawan.karyawan.golongan_darah',
                      'tugas_karyawan.karyawan.jenis_kelamin',
                  'aktivitas_karyawan',
                  'user'
                ])
                ->whereHas('tugas_karyawan', function ($q) use ($request) {
                    $q->where('cabang_id', '=', $request->input('cabang_id'));
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
      'aktivitas_karyawan_id' => 'required|exists:aktivitas_karyawan,id',
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id',
      'parameter_atribut_karyawan_id.*' => 'required|exists:parameter_atribut_karyawan,id'
    ]);

    $formAtributKaryawanModel = new FormAtributKaryawanModel;
    $formAtributKaryawanModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formAtributKaryawanModel->tanggal_form = $request->input('tanggal_form');
    $formAtributKaryawanModel->jam = $request->input('jam');
    $formAtributKaryawanModel->aktivitas_karyawan_id = $request->input('aktivitas_karyawan_id');
    $formAtributKaryawanModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formAtributKaryawanModel->user_id = $request->input('user_id');
    $formAtributKaryawanModel->save();

    foreach ($request->input('parameter_atribut_karyawan_id') as $parameter_atribut_karyawan_id) {
      $formAtributKaryawanDModel = new FormAtributKaryawanDModel;
      $formAtributKaryawanDModel->form_atribut_karyawan_id = $formAtributKaryawanModel->id;
      $formAtributKaryawanDModel->parameter_atribut_karyawan_id = $parameter_atribut_karyawan_id;
      $formAtributKaryawanDModel->user_id = $request->input('user_id');
      $formAtributKaryawanDModel->save();
    }
  }

  public function put(Request $request)
  {
    $request->validate([
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'aktivitas_karyawan_id' => 'nullable|exists:aktivitas_karyawan,id',
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id',
      'form_atribut_karyawan_d_id.*' => 'required|exists:form_atribut_karyawan_d,id',
      'parameter_atribut_karyawan_id.*' => 'required|exists:parameter_atribut_karyawan,id'
    ]);

    $formAtributKaryawanModel = FormAtributKaryawanModel::find($request->input('id'));
    $formAtributKaryawanModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formAtributKaryawanModel->tanggal_form = $request->input('tanggal_form');
    $formAtributKaryawanModel->jam = $request->input('jam');
    $formAtributKaryawanModel->aktivitas_karyawan_id = $request->input('aktivitas_karyawan_id');
    $formAtributKaryawanModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formAtributKaryawanModel->user_id = $request->input('user_id');
    $formAtributKaryawanModel->save();

    foreach ($request->input('parameter_atribut_karyawan_id') as $i => $parameter_atribut_karyawan_id) {
      $formAtributKaryawanDModel = FormAtributKaryawanDModel::find($request->input('form_atribut_karyawan_d_id')[$i]);
      $formAtributKaryawanDModel->form_atribut_karyawan_id = $formAtributKaryawanModel->id;
      $formAtributKaryawanDModel->parameter_atribut_karyawan_id = $parameter_atribut_karyawan_id;
      $formAtributKaryawanDModel->user_id = $request->input('user_id');
      $formAtributKaryawanDModel->save();
    }
  }

  public function delete(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_atribut_karyawan,id',
      'user_id' => 'required|exists:user,id'
    ]);

    $formAtributKaryawanDModel = FormAtributKaryawanDModel::where('form_atribut_karyawan_id', $request->input('id'));
    $formAtributKaryawanDModel->delete();

    $formAtributKaryawanModel = FormAtributKaryawanModel::find($request->input('id'));
    $formAtributKaryawanModel->user_id = $request->input('user_id');
    $formAtributKaryawanModel->save();
    $formAtributKaryawanModel->delete();
  }
}