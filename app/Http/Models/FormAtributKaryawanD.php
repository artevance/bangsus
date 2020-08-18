<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FormAtributKaryawanD extends Model
{
  protected $table = 'form_atribut_karyawan_d';

  use SoftDeletes;

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }

  public function h()
  {
    return $this->belongsTo('App\Http\Models\FormAtributKaryawan');
  }

  public function parameter_atribut_karyawan()
  {
    return $this->belongsTo('App\Http\Models\ParameterAtributKaryawan');
  }
}