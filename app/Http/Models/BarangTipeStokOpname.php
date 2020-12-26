<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class BarangTipeStokOpname extends Model
{
  protected $table = 'barang_tipe_stok_opname';

  public $timestamps = false;

  public function barang()
  {
    return $this->belongsTo('App\Http\Models\Barang');
  }
}