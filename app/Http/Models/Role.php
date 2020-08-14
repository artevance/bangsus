<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  protected $table = 'role';

  public function user()
  {
    return $this->hasMany('App\Http\Models\User');
  }
}