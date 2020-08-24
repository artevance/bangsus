<?php

namespace App\Http\Controllers\Operasional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\KelompokFoto as KelompokFotoModel;
use App\Http\Models\FormFoto as FormFotoModel;
use App\Http\Models\FormDendaFoto as FormDendaFotoModel;
use App\Http\Models\FormDendaFotoD as FormDendaFotoDModel;
use App\Http\Models\GenerateFormDendaFoto as GenerateFormDendaFotoModel;
use App\Http\Models\GenerateFormDendaFotoD as GenerateFormDendaFotoDModel;
use Illuminate\Validation\Rule;

class FormDendaFoto extends Controller
{
  public function index(Request $request)
  {
    $query = [
      'cabang_id' => $request->query('cabang_id', 1),
      'kelompok_foto_id' => $request->query('kelompok_foto_id', 1),
      'tanggal_form' => $request->query('tanggal_form', date('Y-m-d'))
    ];

    $this->title('Form Denda Foto | BangsusSys')
      ->role($request->user()->role->role_code)
      ->query($query);
    return view('operasional.form_denda_foto.wrapper', 
      $this->passParams([
        'cabangs' => CabangModel::all(),
        'kelompokFotos' => KelompokFotoModel::all()
      ]
    ));
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_denda_foto,id'
    ]);

    return ['data' => FormDendaFotoModel::with(['d', 'form_foto'])->find($request->input('id'))];
  }

  public function post(Request $request)
  {
    $request->validate([
      'tugas_karyawan_id' => 'nullable|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'form_foto_id' => 'required|exists:form_foto,id',
      'denda' => 'required|boolean',
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id',
      'denda_foto_id.*' => [
        'nullable',
        Rule::exists('denda_foto', 'id')->where(function ($query) use ($request) {
          $model = FormFotoModel::find($request->input('form_foto_id'));
          $kelompokFotoID = is_null($model)
            ? 0
            : $model->kelompok_foto_id;
          $query->where('kelompok_foto_id', $kelompokFotoID);
        })
      ],
      'nominal.*' => 'nullable|numeric',
      'keterangan.*' => 'nullable|max:200'
    ]);

    $formDendaFotoModel = new FormDendaFotoModel;
    $formDendaFotoModel->tugas_karyawan_id = $request->filled('tugas_karyawan_id') 
      ? $request->input('tugas_karyawan_id')
      : $request->user()->tugas_karyawan_id;
    $formDendaFotoModel->tanggal_form = date('Y-m-d');
    $formDendaFotoModel->jam = $request->input('jam');
    $formDendaFotoModel->form_foto_id = $request->input('form_foto_id');
    $formDendaFotoModel->denda = $request->input('denda');
    $formDendaFotoModel->tidak_kirim = 0;
    $formDendaFotoModel->keterangan = $request->filled('keterangan')
      ? (is_array($request->input('keterangan')) ? '' : $request->input('keterangan'))
      : '';
    $formDendaFotoModel->user_id = $request->input('user_id');
    $formDendaFotoModel->save();

    foreach ($request->input('denda_foto_id', []) as $i => $dendaFotoID) {
      $formDendaFotoDModel = new FormDendaFotoDModel;
      $formDendaFotoDModel->form_denda_foto_id = $formDendaFotoModel->id;
      $formDendaFotoDModel->denda_foto_id = $dendaFotoID;
      $formDendaFotoDModel->nominal = $request->input('nominal.'.$i);
      $formDendaFotoDModel->keterangan = $request->filled('keterangan.'.$i)
        ? $request->input('keterangan.'.$i)
        : '';
      $formDendaFotoDModel->user_id = $request->input('user_id');
      $formDendaFotoDModel->save();
    }
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_denda_foto,id',
      'tugas_karyawan_id' => 'nullable|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'form_foto_id' => 'required|exists:form_foto,id',
      'denda' => 'required|boolean',
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id',
      'denda_foto_id.*' => [
        'nullable',
        Rule::exists('denda_foto', 'id')->where(function ($query) use ($request) {
          $model = FormFotoModel::find($request->input('form_foto_id'));
          $kelompokFotoID = is_null($model)
            ? 0
            : $model->kelompok_foto_id;
          $query->where('kelompok_foto_id', 1);
        })
      ],
      'nominal.*' => 'nullable|numeric',
      'keterangan.*' => 'nullable|max:200'
    ]);

    $formDendaFotoModel = FormDendaFotoModel::find($request->input('id'));
    $formDendaFotoModel->tugas_karyawan_id = $request->filled('tugas_karyawan_id') 
      ? $request->input('tugas_karyawan_id')
      : $request->user()->tugas_karyawan_id;
    $formDendaFotoModel->tanggal_form = $request->input('tanggal_form');
    $formDendaFotoModel->jam = $request->input('jam');
    $formDendaFotoModel->form_foto_id = $request->input('form_foto_id');
    $formDendaFotoModel->denda = $request->input('denda');
    $formDendaFotoModel->keterangan = $request->filled('keterangan')
      ? (is_array($request->input('keterangan')) ? '' : $request->input('keterangan'))
      : '';
    $formDendaFotoModel->user_id = $request->input('user_id');
    $formDendaFotoModel->save();

    foreach (FormDendaFotoDModel::where('form_denda_foto_id', $request->input('id'))->get() as $formDendaFotoDModel) {
      $formDendaFotoDModel->user_id = $request->input('user_id');
      $formDendaFotoDModel->save();
      $formDendaFotoDModel->delete();
    }

    foreach ($request->input('denda_foto_id', []) as $i => $dendaFotoID) {
      $formDendaFotoDModel = new FormDendaFotoDModel;
      $formDendaFotoDModel->form_denda_foto_id = $formDendaFotoModel->id;
      $formDendaFotoDModel->denda_foto_id = $dendaFotoID;
      $formDendaFotoDModel->nominal = $request->input('nominal.'.$i);
      $formDendaFotoDModel->keterangan = $request->filled('keterangan.'.$i)
        ? $request->input('keterangan.'.$i)
        : '';
      $formDendaFotoDModel->user_id = $request->input('user_id');
      $formDendaFotoDModel->save();
    }
  }

  public function delete(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_denda_foto,id',
      'user_id' => 'required|exists:user,id'
    ]);

    $formDendaFotoModel = FormDendaFotoModel::find($request->input('id'));
    $formDendaFotoModel->user_id = $request->input('user_id');
    $formDendaFotoModel->save();

    foreach (FormDendaFotoDModel::where('form_denda_foto_id', $formDendaFotoModel->id) as $formDendaFotoDModel) {
      $formDendaFotoDModel->user_id = $request->input('user_id');
      $formDendaFotoDModel->save();
      $FormDendaFotoDModel->delete();
    }

    $formDendaFotoModel->delete();
  }

  public function generate(Request $request)
  {
    $request->validate([
      'tanggal_form' => 'required|date_format:Y-m-d',
      'user_id' => 'required|exists:user,id'
    ]);

    $generateFormDendaFotoModel = GenerateFormDendaFotoModel::where('tanggal_form', $request->input('tanggal_form'))
      ->first();
    if ( ! is_null($generateFormDendaFotoModel)) {
      foreach ($generateFormDendaFotoModel->d ?? [] as $d) {
        $d->form_denda_foto->form_foto->delete();
        $d->form_denda_foto->delete();
        $d->delete();
      }
      $generateFormDendaFotoModel->delete();
    }

    $kelompokFotoModels = KelompokFotoModel::where('denda_tidak_kirim', 1)
      ->get();
    foreach ($kelompokFotoModels as $kelompokFotoModel) {
      $formFotoModels = FormFotoModel::where('kelompok_foto_id', $kelompokFotoModel->id)->get();
      if ($formFotoModels->count() < $kelompokFotoModel->pengaturan_kelompok_foto->qty_minimum_form) {
        $generateFormDendaFotoModel = new GenerateFormDendaFotoModel;
        $generateFormDendaFotoModel->kelompok_foto_id = $kelompokFotoModel->id;
        $generateFormDendaFotoModel->tanggal_form = $request->input('tanggal_form');
        $generateFormDendaFotoModel->user_id = $request->input('user_id');
        $generateFormDendaFotoModel->save();

        foreach (CabangModel::all() as $cabang) {
          $formFotoModel = new FormFotoModel;
          $formFotoModel->cabang_id = $cabang->id;
          $formFotoModel->tanggal_form = $request->input('tanggal_form');
          $formFotoModel->jam = date('H:i:s');
          $formFotoModel->kelompok_foto_id = $kelompokFotoModel->id;
          $formFotoModel->keterangan = '';
          $formFotoModel->tidak_kirim = 1;
          $formFotoModel->user_id = $request->input('user_id');
          $formFotoModel->save();

          $formDendaFotoModel = new FormDendaFotoModel;
          $formDendaFotoModel->tugas_karyawan_id = $request->user()->tugas_karyawan_id;
          $formDendaFotoModel->tanggal_form = $request->input('tanggal_form');
          $formDendaFotoModel->jam = date('H:i:s');
          $formDendaFotoModel->form_foto_id = $formFotoModel->id;
          $formDendaFotoModel->denda = 1;
          $formDendaFotoModel->tidak_kirim = 1;
          $formDendaFotoModel->keterangan = '';
          $formDendaFotoModel->user_id = $request->input('user_id');
          $formDendaFotoModel->save();

          $formDendaFotoDModel = new FormDendaFotoDModel;
          $formDendaFotoDModel->form_denda_foto_id = $formDendaFotoModel->id;
          $formDendaFotoDModel->denda_foto_id = $kelompokFotoModel->pengaturan_kelompok_foto->denda_foto_id;
          $formDendaFotoDModel->nominal = $kelompokFotoModel->pengaturan_kelompok_foto->denda_foto->nominal;
          $formDendaFotoDModel->keterangan = '';
          $formDendaFotoDModel->user_id = $request->input('user_id');
          $formDendaFotoDModel->save();

          $generateFormDendaFotoDModel = new GenerateFormDendaFotoDModel;
          $generateFormDendaFotoDModel->generate_form_denda_foto_id = $generateFormDendaFotoModel->id;
          $generateFormDendaFotoDModel->form_denda_foto_id = $formDendaFotoModel->id;
          $generateFormDendaFotoDModel->save();
        }
      }
    }
  }
}