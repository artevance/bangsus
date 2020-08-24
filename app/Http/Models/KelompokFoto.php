<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class KelompokFoto extends Model
{
  protected $table = 'kelompok_foto';

  public $timestamps = false;

  public function denda_foto()
  {
    return $this->hasMany('App\Http\Models\DendaFoto');
  }

  public function pengaturan_kelompok_foto()
  {
    return $this->hasOne('App\Http\Models\PengaturanKelompokFoto');
  }
}