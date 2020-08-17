<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FormLPG extends Model
{
  protected $table = 'form_lpg';

  use SoftDeletes;

  public function tugas_karyawan()
  {
    return $this->belongsTo('App\Http\Models\TugasKaryawan');
  }

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }

  public function satuan()
  {
    return $this->belongsTo('App\Http\Models\Satuan');
  }

  public function tipe_proses_lpg()
  {
    return $this->belongsTo('App\Http\Models\TipeProsesLPG');
  }

  public static function cabangHarian($cabangID, $tanggalForm)
  {
    return DB::table('form_lpg')
                ->leftJoin('tugas_karyawan', function ($join) use ($cabangID) {
                  $join->on('tugas_karyawan.id', '=', 'tugas_karyawan_id')
                        ->leftJoin('cabang', 'cabang.id', '=', 'cabang_id')
                        ->leftJoin('divisi', 'divisi.id', '=', 'divisi_id')
                        ->leftJoin('jabatan', 'jabatan.id', '=', 'jabatan_id')
                        ->leftJoin('karyawan', 'karyawan.id', '=', 'karyawan_id');
                })
                ->leftJoin('tipe_proses_lpg', 'tipe_proses_lpg.id', '=', 'tipe_proses_lpg_id')
                ->leftJoin('satuan', 'satuan.id', '=', 'satuan_id')
                ->where('cabang_id', $cabangID)
                ->where('tanggal_form', '=', $tanggalForm)
                ->select(
                  '*',
                  'form_lpg.id AS id'
                )
                ->orderBy('jam', 'ASC')
                ->get();
  }
}