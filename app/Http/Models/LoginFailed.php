<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class LoginFailed extends Model
{
  protected $table = 'login_failed';

  public $timestamps = false;

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }
}