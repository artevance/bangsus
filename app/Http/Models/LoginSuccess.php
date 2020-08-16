<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class LoginSuccess extends Model
{
  protected $table = 'login_success';

  public $timestamps = false;

  public function user()
  {
    return $this->belongsTo('App\Http\Models\User');
  }
}