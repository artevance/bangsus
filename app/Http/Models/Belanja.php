<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Belanja extends Model
{
  protected $table = 'belanja';

  use SoftDeletes;

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }

  public function cabang()
  {
    return $this->belongsTo('App\Http\Models\Cabang');
  }

  public function d()
  {
    return $this->hasMany('App\Http\Models\BelanjaD');
  }

  public function scopeByCabang($q, $id)
  {
    return $q->where('cabang_id', $id);
  }

  public function scopeLocked($q)
  {
    return $q->where('locked', true);
  }

  public function scopeNotLocked($q)
  {
    return $q->where('locked', false);
  }
}