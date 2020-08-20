<?php

namespace App\Http\Controllers;

use App\Http\Models\Gambar;

class Sandbox extends Controller
{
  public function index()
  {
    return view('sandbox');
  }
}