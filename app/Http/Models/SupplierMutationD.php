<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class SupplierMutationD extends Model
{
  protected $table = 'supplier_mutation_d';

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