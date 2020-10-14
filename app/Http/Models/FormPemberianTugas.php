<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FormPemberianTugas extends Model
{
  protected $table = 'form_pemberian_tugas';

  use SoftDeletes;

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }

  public function form_pemberian_tugas_cabang()
  {
    return $this->hasMany('App\Http\Models\FormPemberianTugasCabang')->with(['cabang']);
  }

  public function form_pengumpulan_tugas()
  {
    return $this->hasMany('App\Http\Models\FormPengumpulanTugas')->with(['cabang']);
  }
}