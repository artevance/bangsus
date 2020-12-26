<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Gambar as GambarModel;
use Intervention\Image\Facades\Image;

class Gambar extends Controller
{
  public function index(GambarModel $gambar, Request $request)
  {
    if (is_null($gambar->dir)) {
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

      return response($img->encode('jpg', 70))->header('Content-Type', 'image/jpeg');
    } else {
      return response()->file($gambar->dir);
    }
  }
}