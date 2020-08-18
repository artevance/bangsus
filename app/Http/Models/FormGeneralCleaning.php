<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FormGeneralCleaning extends Model
{
  protected $table = 'form_general_cleaning';

  use SoftDeletes;

  public function tugas_karyawan()
  {
    return $this->belongsTo('App\Http\Models\TugasKaryawan');
  }

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }

  public function kegiatan_general_cleaning()
  {
    return $this->belongsTo('App\Http\Models\KegiatanGeneralCleaning');
  }
}