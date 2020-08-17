<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ParameterQualityControl extends Model
{
  protected $table = 'parameter_quality_control';

  public $timestamps = false;

  public function opsi_parameter_quality_control()
  {
    return $this->hasMany('App\Http\Models\OpsiParameterQualityControl');
  }

  public function quality_control()
  {
    return $this->belongsTo('App\Http\Models\QualityControl');
  }
}