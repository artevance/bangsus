<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
  protected $table = 'cabang';

  public $timestamps = false;

  public function tipe_cabang()
  {
    return $this->belongsTo('App\Http\Models\TipeCabang');
  }
}