<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class GenerateFormDendaFotoD extends Model
{
  protected $table = 'generate_form_denda_foto_d';

  use SoftDeletes;

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }

  public function h()
  {
    return $this->hasMany('App\Http\Models\GenerateFormDendaFoto');
  }

  public function form_denda_foto()
  {
    return $this->belongsTo('App\Http\Models\FormDendaFoto');
  }
}