<?php

namespace App\Http\Controllers\Operasional\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\AtributKaryawan as AtributKaryawanModel;
use App\Http\Models\ParameterAtributKaryawan as ParameterAtributKaryawanModel;

class AtributKaryawan extends Controller
{
  public function atributKaryawan(Request $request)
  {
    $this->title('Quality Control | BangsusSys')->role($request->user()->role->role_code);
    return view('operasional.master.atribut_karyawan.atribut_karyawan.wrapper', $this->passParams());
  }

  public function parameterAtributKaryawan(AtributKaryawanModel $atributKaryawan, Request $request)
  {
    $this->title('Quality Control | BangsusSys')->role($request->user()->role->role_code);
    return view('operasional.master.atribut_karyawan.parameter_atribut_karyawan.wrapper', $this->passParams(['atributKaryawan' => $atributKaryawan, 'atributKaryawans' => AtributKaryawanModel::all()]));
  }

  public function opsiParameterAtributKaryawan(AtributKaryawanModel $atributKaryawan, ParameterAtributKaryawanModel $parameterAtributKaryawan, Request $request)
  {
    $this->title('Quality Control | BangsusSys')->role($request->user()->role->role_code);
    return view('operasional.master.atribut_karyawan.opsi_parameter_atribut_karyawan.wrapper',
      $this->passParams([
        'atributKaryawan' => $atributKaryawan,
        'parameterAtributKaryawan' => $parameterAtributKaryawan,
        'atributKaryawans' => AtributKaryawanModel::all(),
        'parameterAtributKaryawans' => ParameterAtributKaryawanModel::where('atribut_karyawan_id', $atributKaryawan->id)->get()
      ])
    );
  }
}