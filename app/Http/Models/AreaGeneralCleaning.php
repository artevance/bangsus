<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class AreaGeneralCleaning extends Model
{
  protected $table = 'area_general_cleaning';

  public $timestamps = false;

  public function kegiatan_general_cleaning()
  {
    return $this->hasMany('App\Http\Models\KegiatanGeneralCleaning');
  }
}