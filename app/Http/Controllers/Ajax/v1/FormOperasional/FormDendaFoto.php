<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\FormFoto;
use App\Http\Models\FormDendaFoto as FormDendaFotoModel;
use App\Http\Models\FormDendaFotoD;

class FormDendaFoto extends Controller
{
  public function storeDenda(Request $request)
  {
    $request->validate([
      'form_foto_id' => 'required|exists:form_foto,id',
      'keterangan' => 'nullable|max:200',
      'd' => 'required',
      'd.*.denda_foto_id' => [
        'required',
        Rule::exists('denda_foto', 'id')->where(function ($query) use ($request) {
          $model = FormFoto::find($request->input('form_foto_id'));
          $kelompokFotoID = is_null($model)
            ? 0
            : $model->kelompok_foto_id;
          $query->where('kelompok_foto_id', $kelompokFotoID);
        })
      ],
      'd.*.nominal' => 'required|numeric',
      'd.*.keterangan' => 'required|max:200'
    ]);

    $formDendaFotoModel = new FormDendaFotoModel;
    $formDendaFotoModel->tanggal_form = date('Y-m-d');
    $formDendaFotoModel->jam = date('H:i:s');
    $formDendaFotoModel->form_foto_id = $request->input('form_foto_id');
    $formDendaFotoModel->denda = 1;
    $formDendaFotoModel->tidak_kirim = 0;
    $formDendaFotoModel->keterangan = '';
    $formDendaFotoModel->user_id = $request->user()->id;
    $formDendaFotoModel->save();

    foreach ($request->input('d') as $i => $d) {
      $formDendaFotoDModel = new FormDendaFotoD;
      $formDendaFotoDModel->form_denda_foto_id = $formDendaFotoModel->id;
      $formDendaFotoDModel->denda_foto_id = $d['denda_foto_id'];
      $formDendaFotoDModel->nominal = $d['nominal'];
      $formDendaFotoDModel->keterangan = $d['keterangan'];
      $formDendaFotoDModel->user_id = $request->user()->id;
      $formDendaFotoDModel->save();
    }
  }

  public function storeTidakDenda(Request $request)
  {
    $request->validate([
      'form_foto_id' => 'required|exists:form_foto,id'
    ]);

    $formDendaFotoModel = new FormDendaFotoModel;
    $formDendaFotoModel->tanggal_form = date('Y-m-d');
    $formDendaFotoModel->jam = date('H:i:s');
    $formDendaFotoModel->form_foto_id = $request->input('form_foto_id');
    $formDendaFotoModel->denda = 0;
    $formDendaFotoModel->tidak_kirim = 0;
    $formDendaFotoModel->keterangan = '';
    $formDendaFotoModel->user_id = $request->user()->id;
    $formDendaFotoModel->save();
  }

  public function amend(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_denda_foto,id',
      'd.*.denda_foto_id' => [
        'nullable',
        Rule::exists('denda_foto', 'id')->where(function ($query) use ($request) {
          $model = FormFotoModel::find($request->input('form_foto_id'));
          $kelompokFotoID = is_null($model)
            ? 0
            : $model->kelompok_foto_id;
          $query->where('kelompok_foto_id', 1);
        })
      ],
      'd.*.nominal' => 'nullable|numeric',
      'd.*.keterangan.*' => 'nullable|max:200'
    ]);

    $formDendaFotoModel = FormDendaFotoModel::find($request->input('id'));
    $formDendaFotoModel->tanggal_form = date('Y-m-d');
    $formDendaFotoModel->jam = date('H:i:s');
    $formDendaFotoModel->user_id = $request->user()->id;
    $formDendaFotoModel->save();

    FormDendaFotoD::where('form_denda_foto_id', $request->input('id'))->delete();

    foreach ($request->input('d') as $i => $d) {
      $formDendaFotoDModel = new FormDendaFotoD;
      $formDendaFotoDModel->form_denda_foto_id = $formDendaFotoModel->id;
      $formDendaFotoDModel->denda_foto_id = $d['denda_foto_id'];
      $formDendaFotoDModel->nominal = $d['nominal'];
      $formDendaFotoDModel->keterangan = $d['keterangan'];
      $formDendaFotoDModel->user_id = $request->user()->id;
      $formDendaFotoDModel->save();
    }
  }

  public function destroy(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_denda_foto,id'
    ]);

    $formDendaFotoModel = FormDendaFotoModel::find($request->input('id'));
    $formDendaFotoModel->user_id = $request->user()->id;
    $formDendaFotoModel->save();

    if ($formDendaFotoModel->form_foto->tidak_kirim == 1) {
      $formDendaFotoModel->form_foto->user_id = $request->user()->id;
      $formDendaFotoModel->form_foto->delete();
    }

    foreach (FormDendaFotoD::where('form_denda_foto_id', $formDendaFotoModel->id) as $formDendaFotoDModel) {
      $formDendaFotoDModel->user_id = $request->user()->id;
      $formDendaFotoDModel->save();
      $formDendaFotoDModel->delete();
    }

    $formDendaFotoModel->delete();
  }
}