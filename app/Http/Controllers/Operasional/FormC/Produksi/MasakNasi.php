<?php

namespace App\Http\Controllers\Operasional\FormC\Produksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\FormMasakNasi as FormMasakNasiModel;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\Gambar as GambarModel;

class MasakNasi extends Controller
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
      ->nav('masakNasi')
      ->query($query);
    return view('operasional.form_c.produksi.masak_nasi.wrapper', $this->passParams(['cabangs' => CabangModel::all()]));
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_masak_nasi,id'
    ]);

    return ['data' => FormMasakNasiModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' => FormMasakNasiModel::with([])
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
        FormMasakNasiModel::with([
            'tugas_karyawan', 
              'tugas_karyawan.cabang',
                'tugas_karyawan.cabang.tipe_cabang',
              'tugas_karyawan.divisi',
              'tugas_karyawan.jabatan',
              'tugas_karyawan.karyawan',
                'tugas_karyawan.karyawan.golongan_darah',
                'tugas_karyawan.karyawan.jenis_kelamin',
            'satuan',
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
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id',
      'gambar' => 'required'
    ]);

    $gambarModel = new GambarModel;
    $gambarModel->konten = base64_decode(str_replace(' ', '+', explode(',', $request->input('gambar'))[1]));
    $gambarModel->save();

    $formMasakNasiModel = new FormMasakNasiModel;
    $formMasakNasiModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formMasakNasiModel->tanggal_form = $request->input('tanggal_form');
    $formMasakNasiModel->jam = $request->input('jam');
    $formMasakNasiModel->qty = $request->input('qty');
    $formMasakNasiModel->satuan_id = $request->input('satuan_id');
    $formMasakNasiModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formMasakNasiModel->gambar_id = $gambarModel->id;
    $formMasakNasiModel->user_id = $request->input('user_id');
    $formMasakNasiModel->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_goreng,id',
      'tugas_karyawan_id' => 'nullable|exists:tugas_karyawan,id',
      'tanggal_form' => 'nullable|date_format:Y-m-d',
      'jam' => 'nullable',
      'qty' => 'nullable|numeric',
      'satuan_id' => 'nullable|exists:satuan,id',
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id',
      'gambar' => 'nullable'
    ]);

    if ($request->filled('gambar')) {
      $gambarModel = new GambarModel;
      $gambarModel->konten = base64_decode(str_replace(' ', '+', explode(',', $request->input('gambar'))[1]));
      $gambarModel->save();
    }

    $formMasakNasiModel = FormMasakNasiModel::find($request->input('id'));
    if ($request->has('tugas_karyawan_id')) $formMasakNasiModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    if ($request->has('tanggal_form')) $formMasakNasiModel->tanggal_form = $request->input('tanggal_form');
    if ($request->has('jam')) $formMasakNasiModel->jam = $request->input('jam');
    if ($request->has('qty')) $formMasakNasiModel->qty = $request->input('qty');
    if ($request->has('satuan_id')) $formMasakNasiModel->satuan_id = $request->input('satuan_id');
    if ($request->has('keterangan')) $formMasakNasiModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    if ($request->filled('gambar')) $formGorengModel->gambar_id = $gambarModel->id;
    $formMasakNasiModel->user_id = $request->input('user_id');
    $formMasakNasiModel->save();
  }

  public function delete(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_masak_nasi,id',
      'user_id' => 'required|exists:user,id'
    ]);

    $formMasakNasiModel = FormMasakNasiModel::find($request->input('id'));
    $formMasakNasiModel->user_id = $request->input('user_id');
    $formMasakNasiModel->save();
    $formMasakNasiModel->delete();
  }
}