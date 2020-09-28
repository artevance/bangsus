<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PengajuanJadwalAbsensi extends Model
{
  protected $table = 'pengajuan_jadwal_absensi';

  public $timestamps = false;

  public function tipe_absensi()
  {
    return $this->belongsTo('App\Http\Models\TipeAbsensi');
  }
  
  public function tugas_karyawan()
  {
    return $this->belongsTo('App\Http\Models\TugasKaryawan')->with([
      'karyawan',
      'cabang',
      'divisi',
      'jabatan'
    ]);
  }

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }
}