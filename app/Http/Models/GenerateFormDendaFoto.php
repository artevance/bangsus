<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class GenerateFormDendaFoto extends Model
{
  protected $table = 'generate_form_denda_foto';

  use SoftDeletes;

  public function kelompok_foto()
  {
    return $this->belongsTo('App\Http\Models\KelompokFoto');
  }

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }

  public function d()
  {
    return $this->hasMany('App\Http\Models\GenerateFormDendaFotoD');
  }
}