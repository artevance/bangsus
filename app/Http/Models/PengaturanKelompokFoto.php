<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanKelompokFoto extends Model
{
  protected $table = 'pengaturan_kelompok_foto';

  public $timestamps = false;

  public function kelompok_foto()
  {
    return $this->belongsTo('App\Http\Models\KelompokFoto');
  }
}