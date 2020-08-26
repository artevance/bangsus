<?php

namespace App\Http\Controllers\Operasional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\FormAktivitasMarketing as FormAktivitasMarketingModel;
use App\Http\Models\FormFoto as FormFotoModel;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\Gambar as GambarModel;

class FormAktivitasMarketing extends Controller
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

    $this->title('Form Aktivitas Marketing | BangsusSys')
      ->role($request->user()->role->role_code)
      ->query($query);
    return view('operasional.form_aktivitas_marketing.wrapper', $this->passParams(['cabangs' => CabangModel::all()]));
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_aktivitas_marketing,id'
    ]);

    return ['data' => FormAktivitasMarketingModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' =>
        FormAktivitasMarketingModel::with(['tugas_karyawan', 'user'])
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
        FormAktivitasMarketingModel::with([
            'tugas_karyawan', 
              'tugas_karyawan.cabang',
                'tugas_karyawan.cabang.tipe_cabang',
              'tugas_karyawan.divisi',
              'tugas_karyawan.jabatan',
              'tugas_karyawan.karyawan',
                'tugas_karyawan.karyawan.golongan_darah',
                'tugas_karyawan.karyawan.jenis_kelamin',
            'aktivitas_marketing',
            'satuan',
            'item_marketing',
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
      'aktivitas_marketing_id' => 'required|exists:aktivitas_marketing,id',
      'qty' => 'required|numeric',
      'satuan_id' => 'required|exists:satuan,id',
      'item_marketing_id' => 'nullable|exists:item_marketing,id',
      'lokasi' => 'required|max:200',
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id',
      'gambar' => 'required'
    ]);

    $gambarModel = new GambarModel;
    $gambarModel->konten = base64_decode(str_replace(' ', '+', explode(',', $request->input('gambar'))[1]));
    $gambarModel->save();

    $formFotoModel = new FormFotoModel;
    $formFotoModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formFotoModel->tanggal_form = $request->input('tanggal_form');
    $formFotoModel->jam = $request->input('jam');
    $formFotoModel->kelompok_foto_id = 16;
    $formFotoModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formFotoModel->gambar_id = $gambarModel->id;
    $formFotoModel->tidak_kirim = 0;
    $formFotoModel->user_id = $request->input('user_id');
    $formFotoModel->save();

    $formAktivitasMarketingModel = new FormAktivitasMarketingModel;
    $formAktivitasMarketingModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formAktivitasMarketingModel->tanggal_form = $request->input('tanggal_form');
    $formAktivitasMarketingModel->jam = $request->input('jam');
    $formAktivitasMarketingModel->form_foto_id = $formFotoModel->id;
    $formAktivitasMarketingModel->aktivitas_marketing_id = $request->input('aktivitas_marketing_id');
    $formAktivitasMarketingModel->qty = $request->input('qty');
    $formAktivitasMarketingModel->satuan_id = $request->input('satuan_id');
    $formAktivitasMarketingModel->item_marketing_id = $request->input('item_marketing_id');
    $formAktivitasMarketingModel->lokasi = $request->input('lokasi');
    $formAktivitasMarketingModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formAktivitasMarketingModel->gambar_id = $gambarModel->id;
    $formAktivitasMarketingModel->user_id = $request->input('user_id');
    $formAktivitasMarketingModel->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_aktivitas_marketing,id',
      'tugas_karyawan_id' => 'nullable|exists:tugas_karyawan,id',
      'tanggal_form' => 'nullable|date_format:Y-m-d',
      'jam' => 'nullable',
      'aktivitas_marketing_id' => 'nullable|exists:aktivitas_marketing,id',
      'qty' => 'nullable|numeric',
      'satuan_id' => 'nullable|exists:satuan,id',
      'item_marketing_id' => 'nullable|exists:item_marketing,id',
      'lokasi' => 'nullable|max:200',
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id',
      'gambar' => 'nullable'
    ]);

    if ($request->filled('gambar')) {
      $gambarModel = new GambarModel;
      $gambarModel->konten = base64_decode(str_replace(' ', '+', explode(',', $request->input('gambar'))[1]));
      $gambarModel->save();
    }

    $formAktivitasMarketingModel = FormAktivitasMarketingModel::find($request->input('id'));
    if ($request->has('tugas_karyawan_id')) $formAktivitasMarketingModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    if ($request->has('tanggal_form')) $formAktivitasMarketingModel->tanggal_form = $request->input('tanggal_form');
    if ($request->has('jam')) $formAktivitasMarketingModel->jam = $request->input('jam');
    if ($request->has('aktivitas_marketing_id')) $formAktivitasMarketingModel->aktivitas_marketing_id = $request->input('aktivitas_marketing_id');
    if ($request->has('qty')) $formAktivitasMarketingModel->qty = $request->input('qty');
    if ($request->has('satuan_id')) $formAktivitasMarketingModel->satuan_id = $request->input('satuan_id');
    if ($request->has('item_marketing_id')) $formAktivitasMarketingModel->item_marketing_id = $request->input('item_marketing_id');
    if ($request->has('lokasi')) $formAktivitasMarketingModel->lokasi = $request->input('lokasi');
    if ($request->has('keterangan')) $formAktivitasMarketingModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    if ($request->filled('gambar')) $formAktivitasMarketingModel->gambar_id = $gambarModel->id;
    $formAktivitasMarketingModel->user_id = $request->input('user_id');
    $formAktivitasMarketingModel->save();

    $formFotoModel = FormFotoModel::find($formAktivitasMarketingModel->form_foto_id);
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
      'id' => 'required|exists:form_aktivitas_marketing,id',
      'user_id' => 'required|exists:user,id'
    ]);

    $formAktivitasMarketingModel = FormAktivitasMarketingModel::find($request->input('id'));
    $formAktivitasMarketingModel->user_id = $request->input('user_id');
    $formAktivitasMarketingModel->save();

    $formFotoModel = FormFotoModel::find($formAktivitasMarketingModel->form_foto_id);
    if ( ! is_null($formFotoModel)) {
      $formFotoModel->user_id = $request->input('user_id');
      $formFotoModel->save();
      $formFotoModel->delete();
    }

    $formAktivitasMarketingModel->delete();
  }
}