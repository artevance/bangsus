<?php

namespace App\Http\Controllers\Ajax\v1\Absensi;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\Absensi as AbsensiModel;
use App\Http\Models\Cabang;
use App\Http\Models\TipeAbsensi;
use App\Http\Models\TugasKaryawan;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImporAbsensi extends Controller
{
  public function preview(Request $request)
  {
    $v = Validator::make($request->only(
      'reset',
      'file'
    ), [
      'reset' => 'required',
      'file' => 'required|file:xls,xlsx'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    // Second gate
    $v = Validator::make([], []);
    try {
      $filename = $request
        ->file('file')
        ->store('tmp/excel/impor_jadwal');
      $reader = IOFactory::createReader(
        IOFactory::identify($filename)
      );
      $reader->setReadDataOnly(true);
      $reader->setLoadSheetsOnly('Sheet1');
      $jadwalSpreadsheet = $reader->load($filename);
      $jadwalContainer = $jadwalSpreadsheet
        ->getActiveSheet()
        ->toArray();

      $v->after(function ($v) use ($jadwalContainer) {
        if (is_array($jadwalContainer)) {
          if (count($jadwalContainer) < 0) {
            $v->errors()->add('impor_jadwal', 'Pastikan data terletak di "Sheet1"');
          }
        } else {
          $v->errors()->add('impor_jadwal', 'Pastikan data terletak di "Sheet1"');
        }
      });
    } catch (\Exception $e) {
      $v->after(function ($v) use ($request, $e) {
        $v->errors()
          ->add(
            'impor_jadwal', 
            'Terjadi error saat akan membuka file excel: ' . $e->getMessage()
          );
      });
    }
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    // Third gate
    $kodeCabang = $jadwalContainer[1][1] ?? null;
    $month = $jadwalContainer[2][1] ?? null;
    $year = $jadwalContainer[2][2] ?? null;
    $dates = $jadwalContainer[3] ?? null;
    foreach ($dates as $i => $date)
      if (is_null($date))
        unset($dates[$i]);
    $v = Validator::make([
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
    $v->sometimes('dates.*', 'between:1,28', function ($input) {
      return $input->month == 2;
    });
    $v->sometimes('dates.*', 'between:1,30', function ($input) {
      return in_array($input->month, [4, 6, 9, 11]);
    });
    $v->sometimes('dates.*', 'between:1,31', function ($input) {
      return in_array($input->month, [1, 3, 5, 7, 8, 10, 12]);
    });
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    // Fourth gate
    $cabangID = Cabang::where('kode_cabang', $kodeCabang)->first()->id;
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
        foreach ($jadwalData as $i => $d) {
          $d = ! is_null($d)
            ? substr((is_string($d) ? trim($d, "'") : $d), 0, 5)
            : null;
          $d = trim($d);
          $d = $d === '' ? null : $d;
          if ( ! is_null($d)) {
            $data[] = [
              'jam_absen' => $d,
              'no_finger' => $noFinger,
              'tanggal_absensi' => $year . '-' . $month . '-' . $dates[$i]
            ];
          }
        }
        $noFinger = null;
      }
    }

    $v = Validator::make([
      'data' => $data
    ], [
      'data.*' => [
        function ($a, $v, $f) use ($cabangID) {
          $exists = TugasKaryawan::where('cabang_id', $cabangID)
            ->where('no_finger', $v['no_finger'])
            ->where(function ($q) use ($v) {
              $q->where('tanggal_mulai', '<=', $v['tanggal_absensi'])
                ->where(function ($q) use ($v) {
                  $q->where('tanggal_selesai', null)
                    ->orWhere('tanggal_selesai', '>=', $v['tanggal_absensi']);
                });
            })
            ->exists();
          if ( ! $exists) $f('No finger: ' . $v['no_finger'] . ' pada tanggal: ' . $v['tanggal_absensi'] . ' tidak valid');
        }
      ],
      'data.*.jam_absen' => 'required|date_format:H:i',
      'data.*.no_finger' => 'required|numeric',
      'data.*.tanggal_absensi' => 'required|date_format:Y-m-d'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    foreach ($data as $i => $d) {
      $d['tugas_karyawan'] = TugasKaryawan::with(['karyawan'])
        ->where('cabang_id', $cabangID)
        ->where('no_finger', $d['no_finger'])
        ->where(function ($q) use ($d) {
          $q->where('tanggal_mulai', '<=', $d['tanggal_absensi'])
            ->where(function ($q) use ($d) {
              $q->where('tanggal_selesai', null)
                ->orWhere('tanggal_selesai', '>=', $d['tanggal_absensi']);
            });
        })
        ->first();

      $data[$i] = $d;
    }

    return $this->data([
      'cabang' => Cabang::where('kode_cabang', $kodeCabang)->first(),
      'data' => $data,
      'tanggal_awal' => $year . '-' . $month . '-' . $dates[0],
      'tanggal_akhir' => $year . '-' . $month . '-' . $dates[count($dates) - 1]
    ])->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'reset',
      'file'
    ), [
      'reset' => 'required',
      'file' => 'required|file:xls,xlsx'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    // Second gate
    $v = Validator::make([], []);
    try {
      $filename = $request
        ->file('file')
        ->store('tmp/excel/impor_jadwal');
      $reader = IOFactory::createReader(
        IOFactory::identify($filename)
      );
      $reader->setReadDataOnly(true);
      $reader->setLoadSheetsOnly('Sheet1');
      $jadwalSpreadsheet = $reader->load($filename);
      $jadwalContainer = $jadwalSpreadsheet
        ->getActiveSheet()
        ->toArray();

      $v->after(function ($v) use ($jadwalContainer) {
        if (is_array($jadwalContainer)) {
          if (count($jadwalContainer) < 0) {
            $v->errors()->add('impor_jadwal', 'Pastikan data terletak di "Sheet1"');
          }
        } else {
          $v->errors()->add('impor_jadwal', 'Pastikan data terletak di "Sheet1"');
        }
      });
    } catch (\Exception $e) {
      $v->after(function ($v) use ($request, $e) {
        $v->errors()
          ->add(
            'impor_jadwal', 
            'Terjadi error saat akan membuka file excel: ' . $e->getMessage()
          );
      });
    }
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    // Third gate
    $kodeCabang = $jadwalContainer[1][1] ?? null;
    $month = $jadwalContainer[2][1] ?? null;
    $year = $jadwalContainer[2][2] ?? null;
    $dates = $jadwalContainer[3] ?? null;
    foreach ($dates as $i => $date)
      if (is_null($date))
        unset($dates[$i]);
    $v = Validator::make([
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
    $v->sometimes('dates.*', 'between:1,28', function ($input) {
      return $input->month == 2;
    });
    $v->sometimes('dates.*', 'between:1,30', function ($input) {
      return in_array($input->month, [4, 6, 9, 11]);
    });
    $v->sometimes('dates.*', 'between:1,31', function ($input) {
      return in_array($input->month, [1, 3, 5, 7, 8, 10, 12]);
    });
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    // Fourth gate
    $cabangID = Cabang::where('kode_cabang', $kodeCabang)->first()->id;
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
        foreach ($jadwalData as $i => $d) {
          $d = ! is_null($d)
            ? substr((is_string($d) ? trim($d, "'") : $d), 0, 5)
            : null;
          $d = trim($d);
          $d = $d === '' ? null : $d;
          if ( ! is_null($d)) {
            $data[] = [
              'jam_absen' => $d,
              'no_finger' => $noFinger,
              'tanggal_absensi' => $year . '-' . $month . '-' . $dates[$i]
            ];
          }
        }
        $noFinger = null;
      }
    }

    $v = Validator::make([
      'data' => $data
    ], [
      'data.*' => [
        function ($a, $v, $f) use ($cabangID) {
          $exists = TugasKaryawan::where('cabang_id', $cabangID)
            ->where('no_finger', $v['no_finger'])
            ->where(function ($q) use ($v) {
              $q->where('tanggal_mulai', '<=', $v['tanggal_absensi'])
                ->where(function ($q) use ($v) {
                  $q->where('tanggal_selesai', null)
                    ->orWhere('tanggal_selesai', '>=', $v['tanggal_absensi']);
                });
            })
            ->exists();
          if ( ! $exists) $f('No finger: ' . $v['no_finger'] . ' pada tanggal: ' . $v['tanggal_absensi'] . ' tidak valid');
        }
      ],
      'data.*.jam_absen' => 'required|date_format:H:i',
      'data.*.no_finger' => 'required|numeric',
      'data.*.tanggal_absensi' => 'required|date_format:Y-m-d'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    if ($request->input('reset')) {
      foreach ($dates as $date) {
        AbsensiModel::whereHas('tugas_karyawan', function ($q) use ($cabangID) {
          $q->where('cabang_id', $cabangID);
        })->where('tanggal_absensi', $year . '-' . $month . '-' . $date)->update([
          'jam_absen' => null
        ]);
      }
    }

    foreach ($data as $i => $d) {
      AbsensiModel::updateOrCreate([
        'tugas_karyawan_id' => 
          TugasKaryawan::with([])
            ->where('cabang_id', $cabangID)
            ->where('no_finger', $d['no_finger'])
            ->where(function ($q) use ($d) {
              $q->where('tanggal_mulai', '<=', $d['tanggal_absensi'])
                ->where(function ($q) use ($d) {
                  $q->where('tanggal_selesai', null)
                    ->orWhere('tanggal_selesai', '>=', $d['tanggal_absensi']);
                });
            })
            ->first()->id,
        'tipe_absensi_id' => 1,
        'tanggal_absensi' => $d['tanggal_absensi']
      ], [
        'jam_absen' => $d['jam_absen'],
        'user_id' => $request->user()->id
      ]);
    }

    return $this->data([
      'cabang' => Cabang::where('kode_cabang', $kodeCabang)->first(),
      'data' => $data,
      'tanggal_awal' => $year . '-' . $month . '-' . $dates[0],
      'tanggal_akhir' => $year . '-' . $month . '-' . $dates[count($dates) - 1]
    ])->response(200);
  }
}