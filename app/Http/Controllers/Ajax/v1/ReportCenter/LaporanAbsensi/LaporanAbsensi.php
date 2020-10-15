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

use App\Services\Reports\LaporanAbsensi as LaporanAbsensiService;

class LaporanAbsensi extends Controller
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

    $data = LaporanAbsensiService::index($request);
    if ( ! $request->has('export')) {
      return $this->data($data)->response(200);
    }
    
    if ($request->input('export') === 'xlsx') {
      $spreadsheet = new Spreadsheet;
      $sheet = $spreadsheet->getActiveSheet();

      $cabang = Cabang::find($request->input('cabang_id'));

      $container = [
        ['Laporan Presensi'],
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
        $heads[] = '';
        $heads[] = '';
      }
      $heads[] = 'Total Presensi';
      $heads[] = 'Total Hari Terlambat';
      $heads[] = 'Total Keterlambatan';
      $heads[] = 'Total Denda';
      $container[] = $heads;

      $subheads = [null, null, null, null];
      foreach ($data['meta']['dates'] as $date) {
        $subheads[] = 'Jam Jadwal';
        $subheads[] = 'Jam Absen';
        $subheads[] = 'Keterlambatan';
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

        if (is_array($d['absensi'])) {
          foreach ($d['absensi'] as $absensi) {
            $row[] = ! is_null($absensi) ? '\'' . $absensi['jam_jadwal'] : '';
            $row[] = ! is_null($absensi) ? '\'' . $absensi['jam_absen'] : '';
            $row[] = ! is_null($absensi) ? '\'' . $absensi['jam_keterlambatan'] : '';
            $row[] = ! is_null($absensi) ? '\'' . $absensi['denda'] : '';
          }
        }
        $row[] = $d['total_presensi'];
        $row[] = $d['total_hari_terlambat'];
        $row[] = $d['total_keterlambatan'];
        $row[] = $d['total_denda'];

        $container[] = $row;
      }

      $sheet->fromArray(
        $container,
        null,
        'A1'
      );

      $filename = 'Laporan Presensi - ' . $cabang->kode_cabang . ' - ' . $cabang->cabang . '.xlsx';
      $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
      $writer->save($filename);

      return response()
        ->download($filename)->deleteFileAfterSend();
    }
  }
}