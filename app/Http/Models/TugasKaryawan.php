<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class TugasKaryawan extends Model
{
  protected $table = 'tugas_karyawan';

  public $timestamps = false;

  public function karyawan()
  {
    return $this->belongsTo('App\Http\Models\Karyawan');
  }

  public function cabang()
  {
    return $this->belongsTo('App\Http\Models\Cabang');
  }

  public function divisi()
  {
    return $this->belongsTo('App\Http\Models\Divisi');
  }

  public function jabatan()
  {
    return $this->belongsTo('App\Http\Models\Jabatan');
  }

  public function absensi()
  {
    return $this->hasMany('App\Http\Models\Absensi')->with(['tipe_absensi']);
  }

  public function pengajuan_jadwal_absensi()
  {
    return $this->hasMany('App\Http\Models\PengajuanJadwalAbsensi');
  }
}