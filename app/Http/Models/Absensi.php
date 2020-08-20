<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Absensi extends Model
{
  protected $table = 'absensi';

  protected $guarded = [];

  public $timestamps = false;

  public function tipe_absensi()
  {
    return $this->belongsTo('App\Http\Models\TipeAbsensi');
  }

  public function tugas_karyawan()
  {
    return $this->belongsTo('App\Http\Models\TugasKaryawan');
  }

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }

  public static function cabangTipeHarian($cabangID, $tipeAbsensiID, $tanggalAbsensi)
  {
    return DB::table('tugas_karyawan')
                ->leftJoin('absensi', function ($join) use ($tanggalAbsensi, $tipeAbsensiID) {
                    $join->on('tugas_karyawan.id', '=', 'absensi.tugas_karyawan_id')
                          ->where('tipe_absensi_id', $tipeAbsensiID)
                          ->where('tanggal_absensi', $tanggalAbsensi);
                })
                ->leftJoin('karyawan', 'tugas_karyawan.karyawan_id', '=', 'karyawan.id')
                ->leftJoin('cabang', 'tugas_karyawan.cabang_id', '=', 'cabang.id')
                ->leftJoin('jabatan', 'tugas_karyawan.jabatan_id', '=', 'jabatan.id')
                ->leftJoin('divisi', 'tugas_karyawan.divisi_id', '=', 'divisi.id')
                ->where('cabang_id', $cabangID)
                ->where('tanggal_mulai', '<=', $tanggalAbsensi)
                ->where(function ($query) use ($tanggalAbsensi) {
                    $query->where('tanggal_selesai', '>=', $tanggalAbsensi)
                          ->orWhere('tanggal_selesai', null);
                })
                ->select(
                  '*',
                  'tugas_karyawan.id AS id',
                  'absensi.id AS absensi_id',
                  'karyawan.id AS karyawan_id'
                )
                ->get();
  }
}