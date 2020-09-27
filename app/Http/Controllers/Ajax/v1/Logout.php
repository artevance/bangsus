<?php

namespace App\Http\Controllers\Ajax\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Logout extends Controller
{
  public function index(Request $request)
  {
    Auth::guard('web')->logout();

    return $this->data(['message' => 'Logout berhasil'])->response(200);
  }
}