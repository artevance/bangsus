<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FormThawingAyam extends Model
{
  protected $table = 'form_thawing_ayam';

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

  public function supplier()
  {
    return $this->belongsTo('App\Http\Models\Supplier');
  }

  public static function cabangHarian($cabangID, $tanggalForm)
  {
    return DB::table('form_thawing_ayam')
                ->leftJoin('tugas_karyawan', function ($join) use ($cabangID) {
                  $join->on('tugas_karyawan.id', '=', 'tugas_karyawan_id')
                        ->leftJoin('cabang', 'cabang.id', '=', 'cabang_id')
                        ->leftJoin('divisi', 'divisi.id', '=', 'divisi_id')
                        ->leftJoin('jabatan', 'jabatan.id', '=', 'jabatan_id')
                        ->leftJoin('karyawan', 'karyawan.id', '=', 'karyawan_id');
                })
                ->leftJoin('supplier', 'supplier.id', '=', 'supplier_id')
                ->leftJoin('satuan', 'satuan.id', '=', 'satuan_id')
                ->where('cabang_id', $cabangID)
                ->where('tanggal_form', '=', $tanggalForm)
                ->select(
                  '*',
                  'form_thawing_ayam.id AS id'
                )
                ->orderBy('jam', 'ASC')
                ->get();
  }
}