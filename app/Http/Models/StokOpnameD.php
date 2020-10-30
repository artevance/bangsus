<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class StokOpnameD extends Model
{
  protected $table = 'stok_opname_d';

  use SoftDeletes;

  public function barang()
  {
    return $this->belongsTo('App\Http\Models\Barang');
  }

  public function satuan()
  {
    return $this->belongsTo('App\Http\Models\Barang');
  }

  public function gambar()
  {
    return $this->belongsTo('App\Http\Models\Gambar');
  }
}