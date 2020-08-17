<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class QualityControl extends Model
{
  protected $table = 'quality_control';

  public $timestamps = false;

  public function parameter_quality_control()
  {
    return $this->hasMany('App\Http\Models\ParameterQualityControl');
  }
}