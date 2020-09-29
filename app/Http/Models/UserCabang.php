<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class UserCabang extends Model
{
  protected $table = 'user_cabang';

  use SoftDeletes;

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }

  public function cabang()
  {
    return $this->belongsTo('App\Http\Models\Cabang');
  }
}