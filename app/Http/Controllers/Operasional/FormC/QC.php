<?php

namespace App\Http\Controllers\Operasional\FormC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\FormQC as FormQCModel;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\Gambar as GambarModel;

class QC extends Controller
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
      ->query($query);
    return view('operasional.form_c.qc.wrapper', $this->passParams(['cabangs' => CabangModel::all()]));
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_qc,id'
    ]);

    return ['data' => FormQCModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' => FormQCModel::with([])
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
      'data' => FormQCModel::with([
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
      'supplier_id' => 'required|exists:supplier,id',
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id',
      'gambar' => 'required'
    ]);

    $gambarModel = new GambarModel;
    $gambarModel->konten = $request->input('gambar');
    $gambarModel->save();

    $formQCModel = new FormQCModel;
    $formQCModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formQCModel->tanggal_form = $request->input('tanggal_form');
    $formQCModel->jam = $request->input('jam');
    $formQCModel->item_goreng_id = $request->input('item_goreng_id');
    $formQCModel->qty = $request->input('qty');
    $formQCModel->satuan_id = $request->input('satuan_id');
    $formQCModel->supplier_id = $request->input('supplier_id');
    $formQCModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formQCModel->gambar_id = $gambarModel->id;
    $formQCModel->user_id = $request->input('user_id');
    $formQCModel->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_qc,id',
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
      $gambarModel->konten = $request->input('gambar');
      $gambarModel->save();
    }

    $formQCModel = FormQCModel::find($request->input('id'));
    if ($request->has('tugas_karyawan_id')) $formQCModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    if ($request->has('tanggal_form')) $formQCModel->tanggal_form = $request->input('tanggal_form');
    if ($request->has('jam')) $formQCModel->jam = $request->input('jam');
    if ($request->has('item_goreng_id')) $formQCModel->item_goreng_id = $request->input('item_goreng_id');
    if ($request->has('qty')) $formQCModel->qty = $request->input('qty');
    if ($request->has('satuan_id')) $formQCModel->satuan_id = $request->input('satuan_id');
    if ($request->has('supplier_id')) $formQCModel->supplier_id = $request->input('supplier_id');
    if ($request->has('keterangan')) $formQCModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    if ($request->filled('gambar')) $formQCModel->gambar_id = $gambarModel->id;
    $formQCModel->user_id = $request->input('user_id');
    $formQCModel->save();
  }

  public function delete(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_qc,id',
      'user_id' => 'required|exists:user,id'
    ]);

    $formQCModel = FormQCModel::find($request->input('id'));
    $formQCModel->user_id = $request->input('user_id');
    $formQCModel->save();
    $formQCModel->delete();
  }
}