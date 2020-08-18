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
    return $this->belongsTo('App\Http\Models\TugasKaryawan');
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
    return $this->hasMany('App\Http\Models\FormQualityControlD');
  }
}