<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\Role as RoleModel;

class Role extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(RoleModel::where('role_name', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! RoleModel::find($id)->exists()) return $this->response(404);

    return $this->data(RoleModel::find($id))->response(200);
  }
}