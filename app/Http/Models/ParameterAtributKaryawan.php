<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ParameterAtributKaryawan extends Model
{
  protected $table = 'parameter_atribut_karyawan';

  public $timestamps = false;

  public function atribut_karyawan()
  {
    return $this->belongsTo('App\Http\Models\AtributKaryawan');
  }
}