<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FormDendaFoto extends Model
{
  protected $table = 'form_denda_foto';

  protected $appends = ['total'];

  use SoftDeletes;

  public function tugas_karyawan()
  {
    return $this->belongsTo('App\Http\Models\TugasKaryawan');
  }

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }

  public function form_foto()
  {
    return $this->belongsTo('App\Http\Models\FormFoto');
  }

  public function d()
  {
    return $this->hasMany('App\Http\Models\FormDendaFotoD');
  }

  public function getTotalAttribute()
  {
    return $this->d()->sum('nominal');
  }
}