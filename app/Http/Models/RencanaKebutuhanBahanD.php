<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class RencanaKebutuhanBahanD extends Model
{
  protected $table = 'rencana_kebutuhan_bahan_d';

  use SoftDeletes;

  public function barang()
  {
    return $this->belongsTo('App\Http\Models\Barang');
  }

  public function satuan()
  {
    return $this->belongsTo('App\Http\Models\Barang');
  }
}