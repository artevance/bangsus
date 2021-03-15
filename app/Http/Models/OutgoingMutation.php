<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class OutgoingMutation extends Model
{
  protected $table = 'outgoing_mutation';

  use SoftDeletes;

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }

  public function cabang()
  {
    return $this->belongsTo('App\Http\Models\Cabang');
  }

  public function cabang_tujuan()
  {
    return $this->belongsTo('App\Http\Models\Cabang');
  }

  public function tugas_karyawan()
  {
    return $this->belongsTo('App\Http\Models\TugasKaryawan');
  }

  public function d()
  {
    return $this->hasMany('App\Http\Models\OutgoingMutationD');
  }

  public function incoming_mutation()
  {
    return $this->hasMany('App\Http\Models\IncomingMutation');
  }

  public function scopeByCabang($q, $id)
  {
    return $q->where('cabang_id', $id);
  }
}