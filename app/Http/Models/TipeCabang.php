<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class TipeCabang extends Model
{
  protected $table = 'tipe_cabang';

  public $timestamps = false;

  public function cabang()
  {
    return $this->hasMany('App\Http\Models\TipeCabang');
  }
}