<?php

namespace App\Http\Controllers\Operasional\FormC\Produksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\FormGoreng as FormGorengModel;
use App\Http\Models\FormFoto as FormFotoModel;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\Gambar as GambarModel;

class Goreng extends Controller
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
      ->nav('goreng')
      ->query($query);
    return view('operasional.form_c.produksi.goreng.wrapper', $this->passParams(['cabangs' => CabangModel::all()]));
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_goreng,id'
    ]);

    return ['data' => FormGorengModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' =>
        FormGorengModel::with(['tugas_karyawan', 'user'])
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
        FormGorengModel::with([
            'tugas_karyawan', 
              'tugas_karyawan.cabang',
                'tugas_karyawan.cabang.tipe_cabang',
              'tugas_karyawan.divisi',
              'tugas_karyawan.jabatan',
              'tugas_karyawan.karyawan',
                'tugas_karyawan.karyawan.golongan_darah',
                'tugas_karyawan.karyawan.jenis_kelamin',
            'item_goreng',
            'satuan',
            'supplier',
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
      'item_goreng_id' => 'required|exists:item_goreng,id',
      'qty' => 'required|numeric',
      'satuan_id' => 'required|exists:satuan,id',
      'supplier_id' => 'nullable|exists:supplier,id',
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
    $formFotoModel->kelompok_foto_id = 2;
    $formFotoModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formFotoModel->gambar_id = $gambarModel->id;
    $formFotoModel->tidak_kirim = 0;
    $formFotoModel->user_id = $request->input('user_id');
    $formFotoModel->save();

    $formGorengModel = new FormGorengModel;
    $formGorengModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formGorengModel->tanggal_form = $request->input('tanggal_form');
    $formGorengModel->jam = $request->input('jam');
    $formGorengModel->form_foto_id = $formFotoModel->id;
    $formGorengModel->item_goreng_id = $request->input('item_goreng_id');
    $formGorengModel->qty = $request->input('qty');
    $formGorengModel->satuan_id = $request->input('satuan_id');
    $formGorengModel->supplier_id = $request->input('supplier_id');
    $formGorengModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formGorengModel->gambar_id = $gambarModel->id;
    $formGorengModel->user_id = $request->input('user_id');
    $formGorengModel->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_goreng,id',
      'tugas_karyawan_id' => 'nullable|exists:tugas_karyawan,id',
      'tanggal_form' => 'nullable|date_format:Y-m-d',
      'jam' => 'nullable',
      'item_goreng_id' => 'nullable|exists:item_goreng,id',
      'qty' => 'nullable|numeric',
      'satuan_id' => 'nullable|exists:satuan,id',
      'supplier_id' => 'nullable|exists:supplier,id',
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id',
      'gambar' => 'nullable'
    ]);

    if ($request->filled('gambar')) {
      $gambarModel = new GambarModel;
      $gambarModel->konten = base64_decode(str_replace(' ', '+', explode(',', $request->input('gambar'))[1]));
      $gambarModel->save();
    }

    $formGorengModel = FormGorengModel::find($request->input('id'));
    if ($request->has('tugas_karyawan_id')) $formGorengModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    if ($request->has('tanggal_form')) $formGorengModel->tanggal_form = $request->input('tanggal_form');
    if ($request->has('jam')) $formGorengModel->jam = $request->input('jam');
    if ($request->has('item_goreng_id')) $formGorengModel->item_goreng_id = $request->input('item_goreng_id');
    if ($request->has('qty')) $formGorengModel->qty = $request->input('qty');
    if ($request->has('satuan_id')) $formGorengModel->satuan_id = $request->input('satuan_id');
    if ($request->has('supplier_id')) $formGorengModel->supplier_id = $request->input('supplier_id');
    if ($request->has('keterangan')) $formGorengModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    if ($request->filled('gambar')) $formGorengModel->gambar_id = $gambarModel->id;
    $formGorengModel->user_id = $request->input('user_id');
    $formGorengModel->save();

    $formFotoModel = FormFotoModel::find($formGorengModel->form_foto_id);
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
      'id' => 'required|exists:form_goreng,id',
      'user_id' => 'required|exists:user,id'
    ]);

    $formGorengModel = FormGorengModel::find($request->input('id'));
    $formGorengModel->user_id = $request->input('user_id');
    $formGorengModel->save();

    $formFotoModel = FormFotoModel::find($formGorengModel->form_foto_id);
    if ( ! is_null($formFotoModel)) {
      $formFotoModel->user_id = $request->input('user_id');
      $formFotoModel->save();
      $formFotoModel->delete();
    }

    $formGorengModel->delete();
  }
}