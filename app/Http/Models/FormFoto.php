<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FormFoto extends Model
{
  protected $table = 'form_foto';

  use SoftDeletes;

  public function tugas_karyawan()
  {
    return $this->belongsTo('App\Http\Models\TugasKaryawan');
  }

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }

  public function kelompok_foto()
  {
    return $this->belongsTo('App\Http\Models\KelompokFoto');
  }

  public function form_denda_foto()
  {
    return $this->hasOne('App\Http\Models\FormDendaFoto');
  }

  public function cabang()
  {
    return $this->belongsTo('App\Http\Models\Cabang');
  }

  public function gambar()
  {
    return $this->belongsTo('App\Http\Models\Gambar');
  }
}