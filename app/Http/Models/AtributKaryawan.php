<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class AtributKaryawan extends Model
{
  protected $table = 'atribut_karyawan';

  public $timestamps = false;

  public function parameter_atribut_karyawan()
  {
    return $this->hasMany('App\Http\Models\ParameterAtributKaryawan');
  }
}