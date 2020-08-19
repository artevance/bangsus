<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Gambar as GambarModel;

class Gambar extends Controller
{
  public function index(GambarModel $gambar, Request $request)
  {
    return response($gambar->konten)->header('Content-Type', 'image/jpeg');
  }
}