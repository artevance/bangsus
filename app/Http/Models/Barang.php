<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
  protected $table = 'barang';

  public $timestamps = false;

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
}