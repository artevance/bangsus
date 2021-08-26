<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class HargaBarang extends Model
{
  protected $table = 'harga_barang';

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
    return $this->hasMany('App\Http\Models\HargaBarangD');
  }

  public function scopeByCabang($q, $id)
  {
    return $q->where('cabang_id', $id);
  }
}