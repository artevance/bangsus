<?php

namespace App\Http\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
  use Notifiable, HasApiTokens;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'email', 'password',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public $timestamps = false;

  protected $table = 'user';

  public function role()
  {
    return $this->belongsTo('App\Http\Models\Role');
  }

  public function tugas_karyawan()
  {
    return $this->belongsTo('App\Http\Models\TugasKaryawan');
  }

  public function user_cabang()
  {
    return $this->hasMany('App\Http\Models\UserCabang');
  }

  public function hasRole($role)
  {
    return $this->role->role_code == $role;
  }
}
