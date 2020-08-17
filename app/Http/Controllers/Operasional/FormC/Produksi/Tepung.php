<?php

namespace App\Http\Controllers\Operasional\FormC\Produksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\FormTepung as FormTepungModel;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\Gambar as GambarModel;

class Tepung extends Controller
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
      ->nav('tepung')
      ->query($query);
    return view('operasional.form_c.produksi.tepung.wrapper', $this->passParams(['cabangs' => CabangModel::all()]));
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_tepung,id'
    ]);

    return ['data' => FormTepungModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' => FormTepungModel::with(['tugas_karyawan', 'user'])
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
      'data' => FormTepungModel::with([
                  'tugas_karyawan', 
                    'tugas_karyawan.cabang',
                      'tugas_karyawan.cabang.tipe_cabang',
                    'tugas_karyawan.divisi',
                    'tugas_karyawan.jabatan',
                    'tugas_karyawan.karyawan',
                      'tugas_karyawan.karyawan.golongan_darah',
                      'tugas_karyawan.karyawan.jenis_kelamin',
                  'satuan',
                  'tipe_proses_tepung',
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
      'tipe_proses_tepung_id' => 'required|exists:tipe_proses_tepung,id',
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id'
    ]);

    $gambarModel = new GambarModel;
    $gambarModel->konten = $request->input('gambar');
    $gambarModel->save();

    $formTepungModel = new FormTepungModel;
    $formTepungModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formTepungModel->tanggal_form = $request->input('tanggal_form');
    $formTepungModel->jam = $request->input('jam');
    $formTepungModel->qty = $request->input('qty');
    $formTepungModel->satuan_id = $request->input('satuan_id');
    $formTepungModel->tipe_proses_tepung_id = $request->input('tipe_proses_tepung_id');
    $formTepungModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formTepungModel->gambar_id = $gambarModel->id;
    $formTepungModel->user_id = $request->input('user_id');
    $formTepungModel->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_tepung,id',
      'tugas_karyawan_id' => 'nullable|exists:tugas_karyawan,id',
      'tanggal_form' => 'nullable|date_format:Y-m-d',
      'jam' => 'nullable',
      'qty' => 'nullable|numeric',
      'satuan_id' => 'nullable|exists:satuan,id',
      'tipe_proses_tepung_id' => 'nullable|exists:tipe_proses_tepung,id',
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id'
    ]);

    if ($request->filled('gambar')) {
      $gambarModel = new GambarModel;
      $gambarModel->konten = $request->input('gambar');
      $gambarModel->save();
    }

    $formTepungModel = FormTepungModel::find($request->input('id'));
    if ($request->has('tugas_karyawan_id')) $formTepungModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    if ($request->has('tanggal_form')) $formTepungModel->tanggal_form = $request->input('tanggal_form');
    if ($request->has('jam')) $formTepungModel->jam = $request->input('jam');
    if ($request->has('qty')) $formTepungModel->qty = $request->input('qty');
    if ($request->has('satuan_id')) $formTepungModel->satuan_id = $request->input('satuan_id');
    if ($request->has('tipe_proses_tepung_id')) $formTepungModel->tipe_proses_tepung_id = $request->input('tipe_proses_tepung_id');
    if ($request->has('keterangan')) $formTepungModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formTepungModel->user_id = $request->input('user_id');
    $formTepungModel->save();
  }

  public function delete(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_tepung,id',
      'user_id' => 'required|exists:user,id'
    ]);

    $formTepungModel = FormTepungModel::find($request->input('id'));
    $formTepungModel->user_id = $request->input('user_id');
    $formTepungModel->save();
    $formTepungModel->delete();
  }
}