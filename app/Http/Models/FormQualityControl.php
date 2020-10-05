<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FormQualityControl extends Model
{
  protected $table = 'form_quality_control';

  use SoftDeletes;

  public function tugas_karyawan()
  {
    return $this->belongsTo('App\Http\Models\TugasKaryawan')->with([
      'karyawan',
      'cabang'
    ]);
  }

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }

  public function quality_control()
  {
    return $this->belongsTo('App\Http\Models\QualityControl');
  }

  public function d()
  {
    return $this->hasMany('App\Http\Models\FormQualityControlD')->with([
      'opsi_parameter_quality_control',
      'opsi_parameter_quality_control.parameter_quality_control'
    ]);
  }

  public function scopeByCabang($q, $id)
  {
    return $q->whereHas('tugas_karyawan', function ($q) use ($id) {
      $q->where('cabang_id', $id);
    });
  }

  public function scopeByQualityControl($q, $id)
  {
    return $q->whereHas('quality_control', function ($q) use ($id) {
      $q->where('quality_control_id', $id);
    });
  }
}