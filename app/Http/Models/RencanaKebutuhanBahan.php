<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class RencanaKebutuhanBahan extends Model
{
  protected $table = 'rencana_kebutuhan_bahan';

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
    return $this->hasMany('App\Http\Models\RencanaKebutuhanBahanD');
  }

  public function scopeByCabang($q, $id)
  {
    return $q->where('cabang_id', $id);
  }
}