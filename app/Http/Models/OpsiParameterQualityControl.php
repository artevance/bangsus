<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class OpsiParameterQualityControl extends Model
{
  protected $table = 'opsi_parameter_quality_control';

  public $timestamps = false;

  public function opsi_parameter_quality_control()
  {
    return $this->belongsTo('App\Http\Models\ParameterQualityControl');
  }
}