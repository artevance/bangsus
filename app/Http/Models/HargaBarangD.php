<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class HargaBarangD extends Model
{
  protected $table = 'harga_barang_d';

  protected $casts = [
    'qty' => 'float',
    'harga_lama' => 'float',
    'harga_barang' => 'float',
  ];

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