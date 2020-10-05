<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FormAktivitasMarketing extends Model
{
  protected $table = 'form_aktivitas_marketing';

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

  public function aktivitas_marketing()
  {
    return $this->belongsTo('App\Http\Models\AktivitasMarketing');
  }

  public function satuan()
  {
    return $this->belongsTo('App\Http\Models\Satuan');
  }

  public function item_marketing()
  {
    return $this->belongsTo('App\Http\Models\ItemMarketing');
  }

  public function scopeByCabang($q, $id)
  {
    return $q->whereHas('tugas_karyawan', function ($q) use ($id) {
      $q->where('cabang_id', $id);
    });
  }
}