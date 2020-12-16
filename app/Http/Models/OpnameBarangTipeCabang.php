<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class OpnameBarangTipeCabang extends Model
{
  protected $table = 'opname_barang_tipe_cabang';

  public $timestamps = false;

  public function barang()
  {
    return $this->belongsTo('App\Http\Models\Barang');
  }
}