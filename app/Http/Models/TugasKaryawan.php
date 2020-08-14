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
}