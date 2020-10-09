<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FormTugas extends Model
{
  protected $table = 'form_tugas';

  use SoftDeletes;

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }

  public function form_tugas_file()
  {
    return $this->hasMany('App\Http\Models\FormTugasFile');
  }

  public function cabang()
  {
    return $this->belongsTo('App\Http\Models\Cabang');
  }

  public function scopeByCabang($q, $id)
  {
    return $q->where('cabang_id', $id);
  }

  public function scopeStillActive($q)
  {
    return $q->whereNull('waktu_pengumpulan');
  }

  public function scopeWaitingChecker($q)
  {
    return $q->whereNotNull('waktu_pengumpulan')->whereNull('waktu_diperiksa');
  }

  public function scopeIsChecked($q)
  {
    return $q->whereNotNull('waktu_diperiksa');
  }
}