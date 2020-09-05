<?php

namespace App\Http\Controllers\HRD\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\TipeAbsensi as TipeAbsensiModel;
use App\Http\Models\Absensi as AbsensiModel;
use App\Http\Models\TugasKaryawan as TugasKaryawanModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class EksporAbsensi extends Controller
{
  public function index(Request $request)
  {
    $this->title('Ekspor Absensi | BangsusSys')
      ->role(
        $request
          ->user()->role->role_code
        )
      ->query([
          'cabang_id' => $request->query('cabang_id', '*'),
          'tanggal_awal' => $request->query('tanggal_awal', date('Y-m-d')),
          'tanggal_akhir' => $request->query('tanggal_akhir', date('Y-m-d'))
        ]);
    return view('hrd.absensi.ekspor_absensi.wrapper', $this->passParams([
      'cabangs' => CabangModel::all()
    ]));
  }

  public function ekspor(Request $request)
  {
    $request->validate([
      'cabang_id' => 'required|exists:cabang,id',
      'tanggal_awal' => 'required|date_format:Y-m-d',
      'tanggal_akhir' => 'required|date_format:Y-m-d',
      'off' => 'required|numeric',
      'nominal_denda' => 'required|numeric',
      'nominal_maksimal_denda' => 'required|numeric',
      'hari_maksimal_masuk' => 'required|numeric'
    ]);

    $spreadsheet = new Spreadsheet;
    $sheet = $spreadsheet->getActiveSheet();

    $cabangModel = CabangModel::find($request->input('cabang_id'));
    $data = [
      ['Laporan Presensi & Keterlambatan', null],
      ['Kode Cabang', $cabangModel->kode_cabang, 'Tanggal Mulai', $request->input('tanggal_awal')],
      ['Nama Cabang', $cabangModel->cabang, 'Tanggal Selesai', $request->input('tanggal_akhir')],
      ['Tipe Cabang', $cabangModel->tipe_cabang->tipe_cabang],
      [],
      []
    ];

    $head = ['No.', 'NIP', 'Nama Karyawan', 'Jabatan'];
    for ($i = strtotime($request->input('tanggal_awal')); $i <= strtotime($request->input('tanggal_akhir')); $i += 86400) {
      $head[] = date('Y-m-d', $i);
    }
    $head[] = 'Off';
    $head[] = 'Total';

    $data[] = $head;
    $data[] = ['Laporan Presensi'];

    $tugasKaryawans = TugasKaryawanModel::where('cabang_id', $request->input('cabang_id'))
      ->where('cabang_id', $request->input('cabang_id'))
      ->where('tanggal_mulai', '<=', $request->input('tanggal_akhir'))
      ->where(function ($query) use ($request) {
          $query->where('tanggal_selesai', '>=', $request->input('tanggal_awal'))
            ->orWhere('tanggal_selesai', null);
        })->get();


    $presentions = [];

    $startingColumn = 5;
    $startingRow = 9;

    $row = 9;
    $hariMaksimalMasuk = $request->input('hari_maksimal_masuk');
    foreach ($tugasKaryawans as $i => $tugasKaryawan) {
      $presention = [$i + 1, '\'' . $tugasKaryawan->karyawan->nip, $tugasKaryawan->karyawan->nama_karyawan, $tugasKaryawan->jabatan->jabatan];

      for ($i = strtotime($request->input('tanggal_awal')); $i <= strtotime($request->input('tanggal_akhir')); $i += 86400) {
        $absensi = AbsensiModel::where('tugas_karyawan_id', $tugasKaryawan->id)
          ->whereDate('tanggal_absensi', '=', date('Y-m-d', $i))
          ->where('tipe_absensi_id', '=', 1)->first();
        $presention[] = is_null($absensi)
          ? 0
          : (
            is_null($absensi->jam_absen) || is_null($absensi->jam_jadwal)
              ? 0
              : 1
          );
      }
      $presention[] = $request->input('off');
      $startCell = Coordinate::stringFromColumnIndex(5) . $row;
      $endCell = Coordinate::stringFromColumnIndex(5 + ((strtotime($request->input('tanggal_akhir')) - strtotime($request->input('tanggal_awal'))) / 86400) + 1) . $row;
      $row++;

      $presention[] = "=IF(SUM($startCell:$endCell)>$hariMaksimalMasuk,$hariMaksimalMasuk,SUM($startCell:$endCell))";
      $presentions[] = $presention;
    }

    $data = array_merge($data, $presentions);
    $data[] = ['Laporan Denda (Menit)'];


    $row++;
    $presentions = [];
    foreach ($tugasKaryawans as $i => $tugasKaryawan) {
      $presention = [$i + 1, '\'' . $tugasKaryawan->karyawan->nip, $tugasKaryawan->karyawan->nama_karyawan, $tugasKaryawan->jabatan->jabatan];

      for ($i = strtotime($request->input('tanggal_awal')); $i <= strtotime($request->input('tanggal_akhir')); $i += 86400) {
        $absensi = AbsensiModel::where('tugas_karyawan_id', $tugasKaryawan->id)
          ->whereDate('tanggal_absensi', '=', date('Y-m-d', $i))
          ->where('tipe_absensi_id', '=', 1)->first();

        $penalty = (is_null($absensi)
          ? 0
          : (
            is_null($absensi->jam_jadwal) || is_null($absensi->jam_absen)
              ? 0
              : (
                $absensi->jam_jadwal < $absensi->jam_absen
                  ? (strtotime($absensi->jam_absen) / 60) - (strtotime($absensi->jam_jadwal) / 60)
                  : 0
              )
          )
        ) * $request->input('nominal_denda');

        $presention[] = $penalty > $request->input('nominal_maksimal_denda')
          ? $request->input('nominal_maksimal_denda')
          : $penalty;
      }
      $presention[] = null;
      $startCell = Coordinate::stringFromColumnIndex(5) . $row;
      $endCell = Coordinate::stringFromColumnIndex(5 + ((strtotime($request->input('tanggal_akhir')) - strtotime($request->input('tanggal_awal'))) / 86400)) . $row;
      $row++;

      $presention[] = "=SUM($startCell:$endCell)";
      $presentions[] = $presention;
    }
    $data = array_merge($data, $presentions);


    $sheet->fromArray(
      $data,
      null,
      'A1'
    );

    $filename = 'Laporan Presensi.xlsx';
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save($filename);

    return response()
      ->download($filename)->deleteFileAfterSend();
  }
}