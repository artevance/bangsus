<?php

namespace App\Http\Controllers;

use App\Http\Models\Gambar;
use Intervention\Image\Facades\Image;

class Sandbox extends Controller
{
  public function index(\Illuminate\Http\Request $request, $page)
  {
    $limit = $page * 100;
    $offset = $limit - 100;

    $gambars = Gambar::where('konten', '!=', '')->offset($offset)->limit($limit)->get();

    foreach ($gambars as $gambar) {
      $img = Image::make($gambar->konten);

      if ($img->height() > 1000 || $img->width() > 1000)
        if ($img->height() > $img->width())
          $img->resize(null, 500, function ($c) {
            $c->aspectRatio();
          });
        else
          $img->resize(700, null, function ($c) {
            $c->aspectRatio();
          });

      $gambar->konten = $img->encode('jpg', 70);
      $gambar->save();
    }
  }
}