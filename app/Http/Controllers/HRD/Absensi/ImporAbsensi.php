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
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImporAbsensi extends Controller
{
  public function index(Request $request)
  {
    $this->title('Impor Absensi | BangsusSys')
      ->role(
        $request
          ->user()->role->role_code
        );
    return view('hrd.absensi.impor_absensi.wrapper', $this->passParams());
  }

  public function impor(Request $request)
  {
    // First level validation.
    // Here we check if the pre-requisite exists.
    $request->validate([
      'file_absensi' => 'required|file:xls,xlsx',
      'user_id' => 'required|exists:user,id'
    ]);


    // Second level validation is to check whether the PhpSpreadSheet
    // succesfuly read the uploaded file.
    $validator = Validator::make($request->all(), []);
    try {
      $filename = $request
        ->file('file_absensi')
        ->store('tmp/excel/impor_absensi');
      $reader = IOFactory::createReader(
        IOFactory::identify($filename)
      );
      $reader->setReadDataOnly(true);
      $reader->setLoadSheetsOnly('Lap. Log Absen');
      $jadwalSpreadsheet = $reader->load($filename);
      $jadwalContainer = $jadwalSpreadsheet
        ->getActiveSheet()
        ->toArray();
    } catch (\Exception $e) {
      $validator->after(function ($validator) use ($request) {
        $validator->errors()
          ->add(
            'impor_absensi', 
            'Terjadi error: ' . $e->getMessage()
            );
      });
    }

    if ($validator->fails())
      return redirect(url('/hrd/absensi/impor_absensi'))
        ->withErrors($validator)
        ->with('imporAbsensiResult', [
          'danger',
          'Impor Absensi gagal'
        ]);

    
    // Third level validation, is to check the abstract value.
    $kodeCabang = $jadwalContainer[1][1] ?? null;
    $month = $jadwalContainer[2][1] ?? null;
    $year = $jadwalContainer[2][2] ?? null;
    $dates = $jadwalContainer[3] ?? null;
    foreach ($dates as $i => $date)
      if (is_null($date))
        unset($dates[$i]);
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
      return redirect(url('/hrd/absensi/impor_absensi'))
        ->withErrors($validator)
        ->with('imporAbsensiResult', [
          'danger',
          'Impor Absensi gagal'
        ]);


    // Fourth level validation, is to check the given data inside the container.
    $cabangID = CabangModel::where('kode_cabang', $kodeCabang)->first()->id;
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
          if ( ! is_null($d)) {
            $d = substr((is_string($d) ? trim($d, "'") : $d), 0, 5);
            $data[] = [
              'jam_absen' => $d,
              'no_finger' => $noFinger,
              'tanggal_absensi' => $year . '-' . $month . '-' . $dates[$i]
            ];
          }
        $noFinger = null;
      }
    }
    
    // return ['data' => $data, 'cabangID' => $cabangID];

    foreach ($data as $d) {
      $noFinger = $d['no_finger'];
      $validator = Validator::make($d, [
        'jam_absen' => 'required|date_format:H:i',
        'no_finger' => [
          'required',
          'numeric',
          Rule::exists('tugas_karyawan')->where(function ($query) use ($cabangID, $noFinger) {
            $query->where('cabang_id', $cabangID)
              ->where('no_finger', $noFinger);
          })
        ],
        'tanggal_absensi' => 'required|date_format:Y-m-d'
      ]);

      if ($validator->fails())
        return redirect(url('/hrd/absensi/impor_absensi'))
          ->withErrors($validator)
          ->with('imporAbsensiResult', [
            'danger',
            'Impor Absensi gagal'
          ]);
    }

    foreach ($data as $i => $d) {

      AbsensiModel::updateOrCreate([
        'tugas_karyawan_id' => 
          TugasKaryawanModel::with([])
            ->where('cabang_id', $cabangID)
            ->where('no_finger', $d['no_finger'])
            ->first()->id,
        'tipe_absensi_id' => 1,
        'tanggal_absensi' => $d['tanggal_absensi']
      ], [
        'jam_absen' => $d['jam_absen'],
        'user_id' => $request->input('user_id')
      ]);
    }

    return redirect(url('/hrd/absensi/impor_absensi'))
      ->with('imporAbsensiResult', [
        'success',
        'Impor Absensi berhasil. Cabang: ' . CabangModel::find($cabangID)->kode_cabang . ' - ' . CabangModel::find($cabangID)->cabang
      ]);
  }
}