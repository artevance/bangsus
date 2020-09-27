<?php

namespace App\Http\Controllers\Ajax\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\User;

class Login extends Controller
{
  public function index(Request $request)
  {
    $v = Validator::make($request->only('username', 'password'), [
      'username' => 'required',
      'password' => 'required'
    ]);
    $user = User::where('username', $request->input('username'))->first();
    $v->after(function ($v) use ($request, $user) {
      if (is_null($user) || ! Hash::check($request->input('password'), $user->password)) {
        $v->errors()->add('login', 'Username atau password salah');
      }
    });
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    Auth::attempt($request->only('username', 'password'));

    return $this->data(Auth::user())->response(200);
  }
}