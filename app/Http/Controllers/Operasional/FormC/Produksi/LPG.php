<?php

namespace App\Http\Controllers\Operasional\FormC\Produksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\FormLPG as FormLPGModel;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\Gambar as GambarModel;

class LPG extends Controller
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
          'tanggal_form' => date('Y-m-d'),
        ];
      break;
      default :
    }

    $this->title('Form C1 | BangsusSys')
      ->role($request->user()->role->role_code)
      ->nav('lpg')
      ->query($query);
    return view('operasional.form_c.produksi.lpg.wrapper', $this->passParams(['cabangs' => CabangModel::all()]));
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_lpg,id'
    ]);

    return ['data' => FormLPGModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' => FormLPGModel::with(['tugas_karyawan', 'user'])
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
      'data' => FormLPGModel::with([
                  'tugas_karyawan', 
                    'tugas_karyawan.cabang',
                      'tugas_karyawan.cabang.tipe_cabang',
                    'tugas_karyawan.divisi',
                    'tugas_karyawan.jabatan',
                    'tugas_karyawan.karyawan',
                      'tugas_karyawan.karyawan.golongan_darah',
                      'tugas_karyawan.karyawan.jenis_kelamin',
                  'satuan',
                  'tipe_proses_lpg',
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
      'qty' => 'required|numeric',
      'satuan_id' => 'required|exists:satuan,id',
      'tipe_proses_lpg_id' => 'required|exists:tipe_proses_lpg,id',
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id'
    ]);

    $gambarModel = new GambarModel;
    $gambarModel->konten = base64_decode(str_replace(' ', '+', explode(',', $request->input('gambar'))[1]));
    $gambarModel->save();

    $formLPGModel = new FormLPGModel;
    $formLPGModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formLPGModel->tanggal_form = $request->input('tanggal_form');
    $formLPGModel->jam = $request->input('jam');
    $formLPGModel->qty = $request->input('qty');
    $formLPGModel->satuan_id = $request->input('satuan_id');
    $formLPGModel->tipe_proses_lpg_id = $request->input('tipe_proses_lpg_id');
    $formLPGModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formLPGModel->gambar_id = $gambarModel->id;
    $formLPGModel->user_id = $request->input('user_id');
    $formLPGModel->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_lpg,id',
      'tugas_karyawan_id' => 'nullable|exists:tugas_karyawan,id',
      'tanggal_form' => 'nullable|date_format:Y-m-d',
      'jam' => 'nullable',
      'qty' => 'nullable|numeric',
      'satuan_id' => 'nullable|exists:satuan,id',
      'tipe_proses_lpg_id' => 'nullable|exists:tipe_proses_lpg,id',
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id'
    ]);

    if ($request->filled('gambar')) {
      $gambarModel = new GambarModel;
      $gambarModel->konten = base64_decode(str_replace(' ', '+', explode(',', $request->input('gambar'))[1]));
      $gambarModel->save();
    }

    $formLPGModel = FormLPGModel::find($request->input('id'));
    if ($request->has('tugas_karyawan_id')) $formLPGModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    if ($request->has('tanggal_form')) $formLPGModel->tanggal_form = $request->input('tanggal_form');
    if ($request->has('jam')) $formLPGModel->jam = $request->input('jam');
    if ($request->has('qty')) $formLPGModel->qty = $request->input('qty');
    if ($request->has('satuan_id')) $formLPGModel->satuan_id = $request->input('satuan_id');
    if ($request->has('tipe_proses_lpg_id')) $formLPGModel->tipe_proses_lpg_id = $request->input('tipe_proses_lpg_id');
    if ($request->has('keterangan')) $formLPGModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formLPGModel->user_id = $request->input('user_id');
    $formLPGModel->save();
  }

  public function delete(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_lpg,id',
      'user_id' => 'required|exists:user,id'
    ]);

    $formLPGModel = FormLPGModel::find($request->input('id'));
    $formLPGModel->user_id = $request->input('user_id');
    $formLPGModel->save();
    $formLPGModel->delete();
  }
}