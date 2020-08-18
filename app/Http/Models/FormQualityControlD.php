<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FormQualityControlD extends Model
{
  protected $table = 'form_quality_control_d';

  use SoftDeletes;

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }

  public function h()
  {
    return $this->belongsTo('App\Http\Models\FormQualityControl');
  }

  public function opsi_parameter_quality_control()
  {
    return $this->belongsTo('App\Http\Models\OpsiParameterQualityControl');
  }
}