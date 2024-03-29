<?php

namespace App\Http\Controllers\Ajax\v1\ReportCenter\LaporanFormOperasional;

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

use App\Services\Reports\LaporanFormDendaFoto as LaporanFormDendaFotoService;

class LaporanFormDendaFoto extends Controller
{
  public function index(Request $request)
  {
    $v = Validator::make($request->only(
      'cabang_id',
      'report_type',
      'tanggal_awal',
      'tanggal_akhir',
      'export'
    ), [
      'cabang_id' => 'nullable',
      'report_type' => 'required',
      'tanggal_awal' => 'required|date|date_format:Y-m-d|before_or_equal:tanggal_akhir',
      'tanggal_akhir' => 'required|date|date_format:Y-m-d|after_or_equal:tanggal_awal',
      'export' => 'nullable'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    switch ($request->input('report_type')) {
      case 'recap' :
        $data = LaporanFormDendaFotoService::recap($request);
        break;
      default :
        $data = [];
        break;
    }
    if ( ! $request->has('export')) {
      return $this->data($data)->response(200);
    }
    
    if ($request->input('export') === 'xlsx') {
      $spreadsheet = new Spreadsheet;
      $sheet = $spreadsheet->getActiveSheet();


      if ($request->input('report_type') == 'recap') {

        $cabang = Cabang::find($request->input('cabang_id'));

        $container = [
          ['Laporan Form Denda Foto (Laporan Rekap)'],
          ['Kode Cabang', ! is_null($cabang) ? $cabang->kode_cabang : 'Semua'],
          ['Nama Cabang', ! is_null($cabang) ? $cabang->cabang : 'Semua']
        ];
        $container[] = [];

        $heads = ['No.', 'Kelompok Foto', 'Total'];
        $container[] = $heads;

        foreach ($data['data'] as $i => $d) {
          $row = [
            $i + 1,
            $d['kelompok_foto'],
            $d['subtotal'],
          ];

          $container[] = $row;
        }

        $container[] = [
          'Grand Total',
          null,
          $data['total']
        ];

      }

      $sheet->fromArray(
        $container,
        null,
        'A1'
      );

      $filename = 'Laporan Form Denda Foto.xlsx';
      $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
      $writer->save($filename);

      return response()
        ->download($filename)->deleteFileAfterSend();
    }
  }
}