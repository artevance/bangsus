<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
  protected $connection= 'bigguy';

  protected $table = 'tbpembelianNew';

  public $timestamps = false;

  public $incrementing = false;
}
