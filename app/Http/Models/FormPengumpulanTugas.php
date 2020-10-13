<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FormPengumpulanTugas extends Model
{
  protected $table = 'form_pengumpulan_tugas';

  use SoftDeletes;

  public function cabang()
  {
    return $this->belongsTo('App\Http\Models\Cabang');
  }

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }

  public function form_pemberian_tugas()
  {
    return $this->belongsTo('App\Http\Models\FormPemberianTugas');
  }
}