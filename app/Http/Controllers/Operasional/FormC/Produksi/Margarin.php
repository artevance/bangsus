<?php

namespace App\Http\Controllers\Operasional\FormC\Produksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\FormMargarin as FormMargarinModel;
use App\Http\Models\FormFoto as FormFotoModel;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\Gambar as GambarModel;

class Margarin extends Controller
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
      ->nav('margarin')
      ->query($query);
    return view('operasional.form_c.produksi.margarin.wrapper', $this->passParams(['cabangs' => CabangModel::all()]));
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_margarin,id'
    ]);

    return ['data' => FormMargarinModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' => 
        FormMargarinModel::with(['tugas_karyawan', 'user'])
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
        FormMargarinModel::with([
            'tugas_karyawan', 
              'tugas_karyawan.cabang',
                'tugas_karyawan.cabang.tipe_cabang',
              'tugas_karyawan.divisi',
              'tugas_karyawan.jabatan',
              'tugas_karyawan.karyawan',
                'tugas_karyawan.karyawan.golongan_darah',
                'tugas_karyawan.karyawan.jenis_kelamin',
            'satuan',
            'tipe_proses_margarin',
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
      'tipe_proses_margarin_id' => 'required|exists:tipe_proses_margarin,id',
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id'
    ]);

    $gambarModel = new GambarModel;
    $gambarModel->konten = base64_decode(str_replace(' ', '+', explode(',', $request->input('gambar'))[1]));
    $gambarModel->save();

    $formFotoModel = new FormFotoModel;
    $formFotoModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formFotoModel->tanggal_form = $request->input('tanggal_form');
    $formFotoModel->jam = $request->input('jam');
    $formFotoModel->kelompok_foto_id = 7;
    $formFotoModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formFotoModel->gambar_id = $gambarModel->id;
    $formFotoModel->user_id = $request->input('user_id');
    $formFotoModel->save();

    $formMargarinModel = new FormMargarinModel;
    $formMargarinModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formMargarinModel->tanggal_form = $request->input('tanggal_form');
    $formMargarinModel->jam = $request->input('jam');
    $formMargarinModel->form_foto_id = $formFotoModel->id;
    $formMargarinModel->qty = $request->input('qty');
    $formMargarinModel->satuan_id = $request->input('satuan_id');
    $formMargarinModel->tipe_proses_margarin_id = $request->input('tipe_proses_margarin_id');
    $formMargarinModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formMargarinModel->gambar_id = $gambarModel->id;
    $formMargarinModel->user_id = $request->input('user_id');
    $formMargarinModel->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_margarin,id',
      'tugas_karyawan_id' => 'nullable|exists:tugas_karyawan,id',
      'tanggal_form' => 'nullable|date_format:Y-m-d',
      'jam' => 'nullable',
      'qty' => 'nullable|numeric',
      'satuan_id' => 'nullable|exists:satuan,id',
      'tipe_proses_margarin_id' => 'nullable|exists:tipe_proses_margarin,id',
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id'
    ]);

    if ($request->filled('gambar')) {
      $gambarModel = new GambarModel;
      $gambarModel->konten = base64_decode(str_replace(' ', '+', explode(',', $request->input('gambar'))[1]));
      $gambarModel->save();
    }

    $formMargarinModel = FormMargarinModel::find($request->input('id'));
    if ($request->has('tugas_karyawan_id')) $formMargarinModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    if ($request->has('tanggal_form')) $formMargarinModel->tanggal_form = $request->input('tanggal_form');
    if ($request->has('jam')) $formMargarinModel->jam = $request->input('jam');
    if ($request->has('qty')) $formMargarinModel->qty = $request->input('qty');
    if ($request->has('satuan_id')) $formMargarinModel->satuan_id = $request->input('satuan_id');
    if ($request->has('tipe_proses_margarin_id')) $formMargarinModel->tipe_proses_margarin_id = $request->input('tipe_proses_margarin_id');
    if ($request->has('keterangan')) $formMargarinModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formMargarinModel->user_id = $request->input('user_id');
    $formMargarinModel->save();

    $formFotoModel = FormFotoModel::find($formMargarinModel->form_foto_id);
    if ( ! is_null($formFotoModel)) {
      if ($request->has('tugas_karyawan_id')) $formFotoModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
      if ($request->has('tanggal_form')) $formFotoModel->tanggal_form = $request->input('tanggal_form');
      if ($request->has('jam')) $formFotoModel->jam = $request->input('jam');
      if ($request->has('keterangan')) $formFotoModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
      if ($request->filled('gambar')) $formFotoModel->gambar_id = $gambarModel->id;
      $formFotoModel->user_id = $request->input('user_id');
      $formFotoModel->save();
    }
  }

  public function delete(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_margarin,id',
      'user_id' => 'required|exists:user,id'
    ]);

    $formMargarinModel = FormMargarinModel::find($request->input('id'));
    $formMargarinModel->user_id = $request->input('user_id');
    $formMargarinModel->save();

    $formFotoModel = FormFotoModel::find($formMargarinModel->form_foto_id);
    if ( ! is_null($formFotoModel)) {
      $formFotoModel->user_id = $request->input('user_id');
      $formFotoModel->save(); 
      $formFotoModel->delete();
    }

    $formMargarinModel->delete();
  }
}