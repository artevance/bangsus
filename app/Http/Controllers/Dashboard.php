<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Dashboard extends Controller
{
  public function index(Request $request)
  {
    $this->title('Dasbboard | BangsusSys')->role($request->user()->role->role_code);
    return view('dashboards.wrapper', $this->passParams());
  }
}