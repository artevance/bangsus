<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Role as RoleModel;

class Role extends Controller
{
  public function index(Request $request)
  {
    $this->title('Role | BangsusSys')->role($request->user()->role->role_code)->query($request->query());
    return view('role.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:role,id'
    ]);

    return ['data' => RoleModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' => 
        RoleModel::with([])
          ->where('role_name', 'LIKE', '%' . $request->input('q') . '%')
          ->get()
    ];
  }
}