<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Http\Models\Gambar;
use App\Http\Models\FormGoreng;

class Migrasi extends Controller
{
  public function index(Request $request)
  {

    $container = DB::table('db_bangsus_hrd.ops_form_goreng')->get();

    foreach ($container as $data) {
      if ( ! is_null($data->image_dir) && $data->image_dir != '') {
        $filename = explode('public/', $data->image_dir)[1];

        $file = File::get($filename);
        $type = File::mimeType($filename);

        $gambar = new Gambar;
        $gambar->konten = $file;
        $gambar->save();
        exit;

        $formGoreng = FormGoreng::find($data->form_goreng_id);
        $formGoreng->gambar_id = $gambar->id;
        $formGoreng->save();
      }
    }










    return;


    $filename = 'database/img/form_goreng_5f3a0ac8b528b_5f3a0ac8b528f5f3a0ac8b5290_5f3a0ac8b5291.jpeg';

    $file = File::get($filename);
    $type = File::mimeType($filename);

    $gambar = new Gambar;
    $gambar->konten = $file;
    $gambar->save();
  }
}