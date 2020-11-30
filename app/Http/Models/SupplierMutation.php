<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class SupplierMutation extends Model
{
  protected $table = 'supplier_mutation';

  use SoftDeletes;

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }

  public function supplier_mutasi()
  {
    return $this->belongsTo('App\Http\Models\SupplierMutasi');
  }

  public function cabang()
  {
    return $this->belongsTo('App\Http\Models\Cabang');
  }

  public function cabang_asal()
  {
    return $this->belongsTo('App\Http\Models\Cabang');
  }

  public function d()
  {
    return $this->hasMany('App\Http\Models\SupplierMutationD');
  }

  public function scopeByCabang($q, $id)
  {
    return $q->where('cabang_id', $id);
  }
}