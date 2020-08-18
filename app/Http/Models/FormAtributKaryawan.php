<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FormAtributKaryawan extends Model
{
  protected $table = 'form_atribut_karyawan';

  use SoftDeletes;

  public function tugas_karyawan()
  {
    return $this->belongsTo('App\Http\Models\TugasKaryawan');
  }

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }

  public function aktivitas_karyawan()
  {
    return $this->belongsTo('App\Http\Models\AktivitasKaryawan');
  }

  public function d()
  {
    return $this->hasMany('App\Http\Models\FormAtributKaryawanD');
  }
}