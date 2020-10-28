<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class StokOpname extends Model
{
  protected $table = 'stok_opname';

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
    return $this->hasMany('App\Http\Models\StokOpnameD');
  }

  public function scopeByCabang($q, $id)
  {
    return $q->where('cabang_id', $id);
  }
}