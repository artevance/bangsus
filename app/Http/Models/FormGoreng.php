<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FormGoreng extends Model
{
  protected $table = 'form_goreng';

  use SoftDeletes;

  public function tugas_karyawan()
  {
    return $this->belongsTo('App\Http\Models\TugasKaryawan')->with([
      'karyawan',
      'cabang'
    ]);
  }

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }

  public function item_goreng()
  {
    return $this->belongsTo('App\Http\Models\ItemGoreng');
  }

  public function satuan()
  {
    return $this->belongsTo('App\Http\Models\Satuan');
  }

  public function supplier()
  {
    return $this->belongsTo('App\Http\Models\Supplier');
  }

  public function scopeByCabang($q, $id)
  {
    return $q->whereHas('tugas_karyawan', function ($q) use ($id) {
      $q->where('cabang_id', $id);
    });
  }
}