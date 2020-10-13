<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FileTugas extends Model
{
  protected $table = 'file_tugas';

  use SoftDeletes;

  public function form_pengumpulan_tugas()
  {
    return $this->belongsTo('App\Http\Models\FormPengumpulanTugas');
  }
}