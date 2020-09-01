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
      'tanggal_akhir' => 'required|date_format:Y-m-d'
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
      $presentions[] = $presention;
    }

    $data = array_merge($data, $presentions);
    $data[] = ['Laporan Keterlambatan (Menit)'];


    $presentions = [];
    foreach ($tugasKaryawans as $i => $tugasKaryawan) {
      $presention = [$i + 1, '\'' . $tugasKaryawan->karyawan->nip, $tugasKaryawan->karyawan->nama_karyawan, $tugasKaryawan->jabatan->jabatan];

      for ($i = strtotime($request->input('tanggal_awal')); $i <= strtotime($request->input('tanggal_akhir')); $i += 86400) {
        $absensi = AbsensiModel::where('tugas_karyawan_id', $tugasKaryawan->id)
          ->whereDate('tanggal_absensi', '=', date('Y-m-d', $i))
          ->where('tipe_absensi_id', '=', 1)->first();
        $presention[] = is_null($absensi)
          ? 0
          : (
            is_null($absensi->jam_jadwal) && is_null($absensi->jam_absen)
              ? 0
              : (
                $absensi->jam_jadwal < $absensi->jam_absen
                  ? (strtotime($absensi->jam_absen) / 60) - (strtotime($absensi->jam_jadwal) / 60)
                  : 0
              )
          );
      }
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