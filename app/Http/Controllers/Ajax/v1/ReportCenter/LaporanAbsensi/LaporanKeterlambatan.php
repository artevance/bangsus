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

use App\Services\Reports\LaporanKeterlambatan as LaporanKeterlambatanService;

class LaporanKeterlambatan extends Controller
{
  public function index(Request $request)
  {
    $v = Validator::make($request->only(
      'cabang_id',
      'tipe_absensi_id',
      'tanggal_awal',
      'tanggal_akhir',
      'export'
    ), [
      'cabang_id' => 'required|exists:cabang,id',
      'tipe_absensi_id' => 'required|exists:tipe_absensi,id',
      'tanggal_awal' => 'required|date|date_format:Y-m-d|before_or_equal:tanggal_akhir',
      'tanggal_akhir' => 'required|date|date_format:Y-m-d|after_or_equal:tanggal_awal',
      'export' => 'nullable'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $data = LaporanKeterlambatanService::index($request);
    if ( ! $request->has('export')) {
      return $this->data($data)->response(200);
    }
    
    if ($request->input('export') === 'xlsx') {
      $spreadsheet = new Spreadsheet;
      $sheet = $spreadsheet->getActiveSheet();

      $cabang = Cabang::find($request->input('cabang_id'));

      $container = [
        ['Laporan Keterlambatan'],
        ['Kode Cabang', $cabang->kode_cabang],
        ['Nama Cabang', $cabang->cabang],
        ['Tanggal Awal', $request->input('tanggal_awal')],
        ['Tanggal Akhir', $request->input('tanggal_akhir')],
        []
      ];

      $heads = ['No.', 'NIP', 'Nama Karyawan', 'No. Finger'];
      foreach ($data['meta']['dates'] as $date) {
        $heads[] = $date;
        $heads[] = '';
      }
      $heads[] = 'Total Denda';
      $heads[] = 'Total Keterlambatan';
      $heads[] = 'Total Hari Terlambat';
      $container[] = $heads;

      $subheads = [null, null, null, null];
      foreach ($data['meta']['dates'] as $date) {
        $subheads[] = 'Jam Keterlambatan';
        $subheads[] = 'Denda';
      }
      $container[] = $subheads;

      foreach ($data['data'] as $i => $d) {
        $row = [
          $i + 1,
          '\'' . $d['karyawan']['nip'],
          $d['karyawan']['nama_karyawan'],
          $d['no_finger']
        ];

        if (is_array($d['absensi']))
          foreach ($d['absensi'] as $absensi) {
            $row[] = ! is_null($absensi)
              ? '\'' . $absensi['jam_keterlambatan']
              : '';
            $row[] = ! is_null($absensi)
              ? $absensi['denda']
              : '';
          }

        $row[] = $d['total_denda'];
        $row[] = '\'' . $d['total_keterlambatan'];
        $row[] = $d['total_hari_terlambat'];

        $container[] = $row;
      }

      $sheet->fromArray(
        $container,
        null,
        'A1'
      );

      $filename = 'Laporan Keterlambatan - ' . $cabang->kode_cabang . ' - ' . $cabang->cabang . '.xlsx';
      $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
      $writer->save($filename);

      return response()
        ->download($filename)->deleteFileAfterSend();
    }
  }
}