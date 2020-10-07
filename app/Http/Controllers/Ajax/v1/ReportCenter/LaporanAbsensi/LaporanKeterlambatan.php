<?php

namespace App\Http\Controllers\Ajax\v1\ReportCenter\LaporanAbsensi;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

use App\Http\Models\Cabang;
use App\Http\Models\TipeAbsensi;
use App\Http\Models\Absensi;
use App\Http\Models\TugasKaryawan;

class LaporanKeterlambatan extends Controller
{
  public function index(Request $request)
  {
    $v = Validator::make($request->(
      'cabang_id',
      'tipe_absensi_id',
      'tanggal_awal',
      'tanggal_akhir',
      'export'
    ), [
      'cabang_id' => 'required|exists:cabang,id',
      'tipe_absensi_id' => 'required|exists:tipe_absensi,id',
      'tanggal_awal' => 'required|date|date_format:Y-m-d|before:tanggal_akhir',
      'tanggal_akhir' => 'required|date|date_format:Y-m-d|after:tanggal_awal',
      'export' => 'nullable'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $cabang = Cabang::find($request->input('cabang_id'));

    $data = [];

    
  }
}