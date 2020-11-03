<?php

namespace App\Http\Controllers\Ajax\v1;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\User as UserModel;
use App\Http\Models\UserCabang;

class User extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(UserModel::with('role')->where('username', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! UserModel::find($id)->exists()) return $this->response(404);

    return $this->data(UserModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'username',
      'password',
      'role_id',
      'cabang_id'
    ), [
      'username' => 'required|max:200|unique:user,username',
      'password' => 'required|max:200',
      'role_id' => 'required|exists:role,id',
      'cabang_id' => 'nullable',
      'cabang_id.*' => 'exists:cabang,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new UserModel;
    $model->username = $request->input('username');
    $model->password = password_hash($request->input('password'), PASSWORD_DEFAULT);
    $model->role_id = $request->input('role_id');
    $model->active = 1;
    $model->save();

    foreach ($request->input('cabang_id') as $cabangId) {
      $userCabang = new UserCabang;
      $userCabang->user_id = $model->id;
      $userCabang->cabang_id = $cabangId;
      $userCabang->save();
    }

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'username',
      'password',
      'role_id',
      'cabang_id'
    ), [
      'id' => 'required|exists:user,id',
      'username' => 'required|max:200|unique:user,username',
      'password' => 'required|max:200',
      'role_id' => 'required|exists:role,id',
      'cabang_id' => 'nullable',
      'cabang_id.*' => 'exists:cabang,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = UserModel::find($id);
    $model->username = $request->input('username');
    $model->password = password_hash($request->input('password'), PASSWORD_DEFAULT);
    $model->role_id = $request->input('role_id');
    $model->save();

    $model->each('user_cabang',
      function ($userCabang) {
        $userCabang->delete();
      }
    );

    foreach ($request->input('cabang_id') as $cabangId) {
      $userCabang = new UserCabang;
      $userCabang->user_id = $model->id;
      $userCabang->cabang_id = $cabangId;
      $userCabang->save();
    }

    return $this->data(['update_id' => $model->id])->response(200);
  }
}