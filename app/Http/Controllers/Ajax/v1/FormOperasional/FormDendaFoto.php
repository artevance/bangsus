<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\Cabang;
use App\Http\Models\KelompokFoto;
use App\Http\Models\FormFoto;
use App\Http\Models\FormDendaFoto as FormDendaFotoModel;
use App\Http\Models\FormDendaFotoD;
use App\Http\Models\GenerateFormDendaFoto;
use App\Http\Models\GenerateFormDendaFotoD;

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

    return [$request->user()->id];
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

    return [$request->user()->id];
  }

  public function amend(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_denda_foto,id',
      'd.*.denda_foto_id' => [
        'required',
        'exists:denda_foto,id'
      ],
      'd.*.nominal' => 'required|numeric',
      'd.*.keterangan' => 'required|max:200'
    ]);

    $formDendaFotoModel = FormDendaFotoModel::find($request->input('id'));
    $formDendaFotoModel->tanggal_form = date('Y-m-d');
    $formDendaFotoModel->jam = date('H:i:s');
    $formDendaFotoModel->keterangan = '';
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

    return [$request->user()->id];
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

  public function generate(Request $request)
  {
    $request->validate([
      'cabang_id' => 'required|exists:cabang,id',
      'tanggal_awal' => 'required|date_format:Y-m-d',
      'tanggal_akhir' => 'required|date_format:Y-m-d',
      'detail.*.id' => 'required|exists:kelompok_foto,id',
      'detail.*.qty_toleransi' => 'required|numeric'
    ]);

    $cabang = Cabang::find($request->input('cabang_id'));

    $tanggalAwalTimestamp = strtotime($request->input('tanggal_awal'));
    $tanggalAkhirTimestamp = strtotime($request->input('tanggal_akhir'));
    while ($tanggalAwalTimestamp <= $tanggalAkhirTimestamp) {
      $tanggalForm = date('Y-m-d', $tanggalAwalTimestamp);

      $generateFormDendaFotoModels = GenerateFormDendaFoto::where('tanggal_form', $tanggalForm)
        ->whereHas('d.form_denda_foto.form_foto', function ($q) use ($cabang) {
          $q->where('cabang_id', $cabang->id);
        })
        ->get();
      if ( ! is_null($generateFormDendaFotoModels)) {
        foreach ($generateFormDendaFotoModels as $generateFormDendaFotoModel) {
          foreach ($generateFormDendaFotoModel->d ?? [] as $d) {
            $d->form_denda_foto->form_foto->delete();
            $d->form_denda_foto->delete();
            $d->delete();
          }
          $generateFormDendaFotoModel->delete();
        }
      }

      foreach ($request->input('detail') as $d) {
        $kelompokFotoModel = KelompokFoto::where('denda_tidak_kirim', 1)->where('id', $d['id'])->first();
        if (is_null($kelompokFotoModel)) continue;

        $generateFormDendaFotoModel = new GenerateFormDendaFoto;
        $generateFormDendaFotoModel->kelompok_foto_id = $kelompokFotoModel->id;
        $generateFormDendaFotoModel->tanggal_form = $tanggalForm;
        $generateFormDendaFotoModel->user_id = $request->user()->id;
        $generateFormDendaFotoModel->save();

        $formFotoModels = FormFoto::where('kelompok_foto_id', $kelompokFotoModel->id)
          ->where('tanggal_form', $tanggalForm)
          ->whereHas('tugas_karyawan', function ($q) use ($cabang) {
            $q->where('cabang_id', $cabang->id);
          })
          ->get();
        $count = $formFotoModels->count() ?? 0;

        $qty_minimum_form = $kelompokFotoModel->pengaturan_kelompok_foto->qty_minimum_form - $d['qty_toleransi'];
        if ($count < $qty_minimum_form) {
          $deficit = $qty_minimum_form - $count;

          for ($i = 0; $i < $deficit; $i++) {
            $formFotoModel = new FormFoto;
            $formFotoModel->cabang_id = $cabang->id;
            $formFotoModel->tanggal_form = $tanggalForm;
            $formFotoModel->jam = date('H:i:s');
            $formFotoModel->kelompok_foto_id = $kelompokFotoModel->id;
            $formFotoModel->keterangan = '';
            $formFotoModel->tidak_kirim = 1;
            $formFotoModel->user_id = $request->user()->id;
            $formFotoModel->save();

            $formDendaFotoModel = new FormDendaFotoModel;
            $formDendaFotoModel->tugas_karyawan_id = $request->user()->tugas_karyawan_id;
            $formDendaFotoModel->tanggal_form = $tanggalForm;
            $formDendaFotoModel->jam = date('H:i:s');
            $formDendaFotoModel->form_foto_id = $formFotoModel->id;
            $formDendaFotoModel->denda = 1;
            $formDendaFotoModel->tidak_kirim = 1;
            $formDendaFotoModel->keterangan = '';
            $formDendaFotoModel->user_id = $request->user()->id;
            $formDendaFotoModel->save();

            $formDendaFotoDModel = new FormDendaFotoD;
            $formDendaFotoDModel->form_denda_foto_id = $formDendaFotoModel->id;
            $formDendaFotoDModel->denda_foto_id = $kelompokFotoModel->pengaturan_kelompok_foto->denda_foto_id;
            $formDendaFotoDModel->nominal = $kelompokFotoModel->pengaturan_kelompok_foto->denda_foto->nominal;
            $formDendaFotoDModel->keterangan = '';
            $formDendaFotoDModel->user_id = $request->user()->id;
            $formDendaFotoDModel->save();

            $generateFormDendaFotoDModel = new GenerateFormDendaFotoD;
            $generateFormDendaFotoDModel->generate_form_denda_foto_id = $generateFormDendaFotoModel->id;
            $generateFormDendaFotoDModel->form_denda_foto_id = $formDendaFotoModel->id;
            $generateFormDendaFotoDModel->save();
          }
        }      
      }

      $tanggalAwalTimestamp += 86400;
    }

  }
}
