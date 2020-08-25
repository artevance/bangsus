<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class DendaFoto extends Model
{
  protected $table = 'denda_foto';

  public $timestamps = false;

  public function kelompok_foto()
  {
    return $this->belongsTo('App\Http\Models\KelompokFoto');
  }

  public function form_denda_foto_d()
  {
    return $this->hasMany('App\Http\Models\FormDendaFotoD');
  }
}