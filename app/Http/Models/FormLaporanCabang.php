<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FormLaporanCabang extends Model
{
  protected $table = 'form_laporan_cabang';

  use SoftDeletes;

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }

  public function cabang()
  {
    return $this->belongsTo('App\Http\Models\Cabang');
  }

  public function file_laporan_cabang()
  {
    return $this->hasMany('App\Http\Models\FileLaporanCabang');
  }
}