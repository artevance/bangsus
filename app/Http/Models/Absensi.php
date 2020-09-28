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