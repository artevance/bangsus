<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
  protected $table = 'barang';

  public $timestamps = false;

  protected $casts = [
    'mutation' => 'boolean',
    'purchase_order' => 'boolean',
    'semua_tipe_cabang' => 'boolean',
    'semua_tipe_stok_opname' => 'boolean',
  ];

  public function satuan()
  {
    return $this->belongsTo('App\Http\Models\Satuan');
  }

  public function satuan_dua()
  {
    return $this->belongsTo('App\Http\Models\Satuan');
  }

  public function satuan_tiga()
  {
    return $this->belongsTo('App\Http\Models\Satuan');
  }

  public function satuan_empat()
  {
    return $this->belongsTo('App\Http\Models\Satuan');
  }

  public function satuan_lima()
  {
    return $this->belongsTo('App\Http\Models\Satuan');
  }

  public function opname_barang_tipe_cabang()
  {
    return $this->hasMany('App\Http\Models\OpnameBarangTipeCabang');
  }

  public function barang_tipe_stok_opname()
  {
    return $this->hasMany('App\Http\Models\BarangTipeStokOpname');
  }
}