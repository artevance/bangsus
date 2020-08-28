<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\User as UserModel;

class User extends Controller
{
  public function index(Request $request)
  {
    $this->title('User | BangsusSys')->role($request->user()->role->role_code)->query($request->query());
    return view('auth.user.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:user,id'
    ]);

    return ['data' => UserModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' => 
        UserModel::with(['role', 'tugas_karyawan', 'tugas_karyawan.cabang', 'tugas_karyawan.karyawan'])
          ->where('username', 'LIKE', '%' . $request->input('q') . '%')
          ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'username' => 'required|max:200',
      'role_id' => 'required|exists:role,id',
      'password' => 'required|min:6',
      'password_verify' => 'required|same:password',
      'tugas_karyawan_id' => 'nullable|exists:tugas_karyawan,id'
    ]);

    $model = new UserModel;
    $model->username = $request->input('username');
    $model->password = password_hash($request->input('password'), PASSWORD_DEFAULT);
    $model->role_id = $request->input('role_id');
    $model->active = 1;
    $model->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:user,id',
      'username' => 'required|max:200'
    ]);

    $model = UserModel::find($request->input('id'));
    if ($request->has('username')) $model->username = strtoupper($request->input('username'));
    $model->save();
  }
}