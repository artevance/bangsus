<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FileLaporanCabang extends Model
{
  protected $table = 'file_laporan_cabang';

  use SoftDeletes;

  public function form_laporan_cabang()
  {
    return $this->belongsTo('App\Http\Models\FormLaporanCabang');
  }
}