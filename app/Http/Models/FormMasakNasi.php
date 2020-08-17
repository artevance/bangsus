<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FormMasakNasi extends Model
{
  protected $table = 'form_masak_nasi';

  use SoftDeletes;

  public function tugas_karyawan()
  {
    return $this->belongsTo('App\Http\Models\TugasKaryawan');
  }

  public function satuan()
  {
    return $this->belongsTo('App\Http\Models\Satuan');
  }

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }

  public static function cabangHarian($cabangID, $tanggalForm)
  {
    return DB::table('form_masak_nasi')
                ->leftJoin('tugas_karyawan', function ($join) use ($cabangID) {
                  $join->on('tugas_karyawan.id', '=', 'tugas_karyawan_id')
                        ->leftJoin('cabang', 'cabang.id', '=', 'cabang_id')
                        ->leftJoin('divisi', 'divisi.id', '=', 'divisi_id')
                        ->leftJoin('jabatan', 'jabatan.id', '=', 'jabatan_id')
                        ->leftJoin('karyawan', 'karyawan.id', '=', 'karyawan_id');
                })
                ->leftJoin('satuan', 'satuan.id', '=', 'satuan_id')
                ->where('cabang_id', $cabangID)
                ->where('tanggal_form', '=', $tanggalForm)
                ->select(
                  '*',
                  'form_masak_nasi.id AS id'
                )
                ->orderBy('jam', 'ASC')
                ->get();
  }
}