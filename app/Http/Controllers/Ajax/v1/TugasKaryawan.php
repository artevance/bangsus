<?php

namespace App\Http\Controllers\Ajax\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\TugasKaryawan as TugasKaryawanModel;

class TugasKaryawan extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(TugasKaryawanModel::get())
      ->response(200);
  }

  public function parent(Request $request, $id)
  {
    return $this
      ->data(
        TugasKaryawanModel::with(['karyawan', 'cabang', 'divisi', 'jabatan'])
          ->where('karyawan_id', $id)
          ->get()
        )
      ->response(200);
  }
}