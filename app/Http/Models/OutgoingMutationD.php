<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class OutgoingMutationD extends Model
{
  protected $table = 'outgoing_mutation_d';

  protected $casts = [
    'qty' => 'float',
    'harga_barang'
  ];

  use SoftDeletes;

  public function barang()
  {
    return $this->belongsTo('App\Http\Models\Barang');
  }

  public function gambar()
  {
    return $this->belongsTo('App\Http\Models\Gambar');
  }
}