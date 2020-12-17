<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class FailedJob extends Model
{
  protected $table = 'failed_job';

  public $timestamps = false;

  protected $casts = [
    'payload' => 'array'
  ];
}
