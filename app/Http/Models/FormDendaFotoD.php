<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FormDendaFotoD extends Model
{
  protected $table = 'form_denda_foto_d';

  use SoftDeletes;
  
  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }

  public function denda_foto()
  {
    return $this->belongsTo('App\Http\Models\DendaFoto');
  }

  public function form_denda_foto()
  {
    return $this->belongsTo('App\Http\Models\FormDendaFoto');
  }
}