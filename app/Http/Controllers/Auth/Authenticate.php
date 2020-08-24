<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Models\LoginSuccess;
use App\Http\Models\LoginFailed;

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
      function ($validator) use ($credentials, $request) {
        if ( ! Auth::attempt($credentials))
          $validator->errors()->add('login', 'The username or password is wrong');
      }
    );
    
    if ($validator->fails()) {
      $model = new LoginFailed;
      $model->datetime = date('Y-m-d H:i:s');
      $model->ip_address = $request->ip();
      $model->attempted_username = $credentials['username'];
      $model->attempted_password = $credentials['password'];
      $model->save();
      return redirect(url('/login'))
        ->withErrors($validator)
        ->withInput();
    }

    $model = new LoginSuccess;
    $model->user_id = Auth::user()->id;
    $model->datetime = date('Y-m-d H:i:s');
    $model->ip_address = $request->ip();
    $model->save();

    

    return redirect()->intended(url('/dashboard'));
  }
}