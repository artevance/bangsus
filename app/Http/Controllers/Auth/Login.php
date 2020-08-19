<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class Login extends Controller
{
  public function index()
  {
    $this->title('Login ke BangsusSys');
    return view('auth.login', $this->passParams());
  }
}