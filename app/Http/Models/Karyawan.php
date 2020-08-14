<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Karyawan extends Model
{
  protected $table = 'karyawan';

  public $timestamps = false;

  public function golongan_darah()
  {
    return $this->belongsTo('App\Http\Models\GolonganDarah');
  }

  public function jenis_kelamin()
  {
    return $this->belongsTo('App\Http\Models\JenisKelamin');
  }

  public function getNip($kodeCabang, $tanggalMulai)
  {
    $tanggalMulai = date('dmy', strtotime($tanggalMulai));
    $inc = 1;
    $nip = $kodeCabang . $tanggalMulai . $this->mixin($inc, '0', 3);

    while (DB::table($this->table)->where('nip', $nip)->exists()) {
      $inc++;
      $nip = $kodeCabang . $tanggalMulai . $this->mixin($inc, '0', 3);      
    }

    return $nip;
  }

  protected function mixin($data, $addon, $digit)
  {
    $data = '1';
    $return = $data;
    for ($i = 0; $i < $digit - strlen($data); $i++) $return = $addon . $return;
    return $return;
  }
}