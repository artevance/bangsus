<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanGeneralCleaning extends Model
{
  protected $table = 'kegiatan_general_cleaning';

  public $timestamps = false;

  public function area_general_cleaning()
  {
    return $this->belongsTo('App\Http\Models\AreaGeneralCleaning');
  }

  public function form_general_cleaning()
  {
    return $this->hasMany('App\Http\Models\FormGeneralCleaning');
  }
}