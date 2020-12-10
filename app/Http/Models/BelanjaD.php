<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class BelanjaD extends Model
{
  protected $table = 'belanja_d';

  use SoftDeletes;

  public function barang()
  {
    return $this->belongsTo('App\Http\Models\Barang');
  }

  public function satuan()
  {
    return $this->belongsTo('App\Http\Models\Satuan');
  }

  public function gambar()
  {
    return $this->belongsTo('App\Http\Models\Gambar');
  }
}