<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FormLaporanFoto extends Model
{
  protected $table = 'form_laporan_foto';

  use SoftDeletes;

  public function tugas_karyawan()
  {
    return $this->belongsTo('App\Http\Models\TugasKaryawan');
  }

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }

  public function kelompok_laporan_foto()
  {
    return $this->belongsTo('App\Http\Models\KelompokLaporanFoto');
  }

  public function cabang()
  {
    return $this->belongsTo('App\Http\Models\Cabang');
  }
}