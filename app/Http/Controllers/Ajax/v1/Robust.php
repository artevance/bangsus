<?php

namespace App\Http\Controllers\Ajax\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\User;

class Robust extends Controller
{
  public function index(Request $request)
  {
    $authentication = ! is_null($request->user());
    $internetConn = true;
    $user = User::with(['role'])->find(Auth::id());

    return $this->data([
      'authentication' => $authentication,
      'internetConn' => $internetConn,
      'user' => $user
    ])->response(200);
  }
}