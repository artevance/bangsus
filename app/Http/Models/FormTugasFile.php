<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FormTugasFile extends Model
{
  protected $table = 'form_tugas_file';

  use SoftDeletes;

  public function form_tugas()
  {
    return $this->belongsTo('App\Http\Models\FormTugasFile');
  }
}