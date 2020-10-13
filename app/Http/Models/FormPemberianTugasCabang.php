<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FormPemberianTugasCabang extends Model
{
  protected $table = 'form_pemberian_tugas_cabang';

  use SoftDeletes;

  public function cabang()
  {
    return $this->belongsTo('App\Http\Models\Cabang');
  }

  public function form_pemberian_tugas()
  {
    return $this->belongsTo('App\Http\Models\FormPemberianTugas');
  }
}