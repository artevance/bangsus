<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Authenticate extends Controller
{
  public function index(Request $request)
  {
    $request->validate([
      'username' => 'required',
      'password' => 'required'
    ]);

    $credentials = $request->only('username', 'password');
    $credentials['active'] = 1;
    $validator = Validator::make($credentials, []);

    $validator->after(
      function ($validator) use ($credentials) {
        if ( ! Auth::attempt($credentials))
          $validator->errors()->add('login', 'The username or password is wrong');
      }
    );
    
    if ($validator->fails())
      return redirect(url('/login'))
        ->withErrors($validator)
        ->withInput();

    return redirect()->intended(url('/dashboard'));
  }
}