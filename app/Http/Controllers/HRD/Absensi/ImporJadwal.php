<?php

namespace App\Http\Controllers\HRD\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\TipeAbsensi as TipeAbsensiModel;
use App\Http\Models\Absensi as AbsensiModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImporJadwal extends Controller
{
  public function index(Request $request)
  {
    $this->title('Impor Jadwal | BangsusSys')
      ->role(
        $request
          ->user()->role->role_code
        );
    return view('hrd.absensi.impor_jadwal.wrapper', $this->passParams());
  }

  public function impor(Request $request)
  {
    $request->validate([
      'file_jadwal' => 'required|file:xls,xlsx',
      'user_id' => 'required|exists:user,id'
    ]);

    $validator = Validator::make($request->all(), []);
    try {
      $filename = $request
        ->file('file_jadwal')
        ->store('tmp/excel/impor_jadwal');
      $reader = IOFactory::createReader(
        IOFactory::identify($filename)
      );
      $reader->setReadDataOnly(true);
      $jadwalSpreadsheet = $reader->load($filename);
      $jadwalContainer = $jadwalSpreadsheet
        ->getActiveSheet()
        ->toArray();
    } catch (\Exception $e) {
      $validator->after(function ($validator) use ($request) {
        $validator->errors()
          ->add(
            'impor_jadwal', 
            'Terjadi error: ' . $e->getMessage()
            );
      });
    }

    if ($validator->fails())
      return redirect(url('/hrd/absensi/impor_jadwal'))->withErrors($validator);

    
    $kodeCabang = $jadwalContainer[1][1] ?? null;
    $month = $jadwalContainer[2][1] ?? null;
    $year = $jadwalContainer[2][2] ?? null;
    $dates = $jadwalContainer[3] ?? null;
    $validator = Validator::make([
      'kode_cabang' => $kodeCabang,
      'month' => $month,
      'year' => $year,
      'dates' => $dates
    ], [
      'kode_cabang' => 'required|exists:cabang,kode_cabang',
      'month' => 'required|numeric|between:1,12',
      'year' => 'required|numeric|digits:4',
      'dates.*' => 'required|numeric|distinct'
    ]);
    $validator->sometimes('dates.*', 'between:1,28', function ($input) {
      return $input->month == 2;
    });
    $validator->sometimes('dates.*', 'between:1,30', function ($input) {
      return in_array($input->month, [4, 6, 9, 11]);
    });
    $validator->sometimes('dates.*', 'between:1,31', function ($input) {
      return in_array($input->month, [1, 3, 5, 7, 8, 10, 12]);
    });

    if ($validator->fails())
      return redirect(url('/hrd/absensi/impor_jadwal'))->withErrors($validator);

    $month = strlen($month) < 2 ? '0' . $month : $month;
    foreach ($dates as $i => $date)
      $dates[$i] = strlen($date) < 2 ? '0' . $date : $date;
    $data = [];
    $noFinger = null;
    foreach ($jadwalContainer as $index => $jadwalData) {
      if ($index <= 3) continue;

      if ($jadwalData[0] === 'ID:') {
        $noFinger = $jadwalData[2];
        continue;
      }

      if ( ! is_null($noFinger)) {
        foreach ($jadwalData as $i => $d)
          if ( ! is_null($d))
            $d = is_string($d) ? trim($d, "'") : $d;
            $data[] = [
              'jam_jadwal' => $d,
              'no_finger' => $noFinger,
              'tanggal_absensi' => $year . '-' . $month . '-' . $dates[$i]
            ];
        $noFinger = null;
      }
    }

    return ['data' => $data];
  }
}