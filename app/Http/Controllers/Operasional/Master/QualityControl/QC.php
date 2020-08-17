<?php

namespace App\Http\Controllers\Operasional\Master\QualityControl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\QC as QCModel;

class QC extends Controller
{
  public function index(Request $request)
  {
    $this->title('Quality Control | BangsusSys')->role($request->user()->role->role_code);
    return view('operasional.master.qc.qc.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:qc,id'
    ]);

    return ['data' => QCModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' => QCModel::with([])
                  ->where('qc', 'LIKE', '%' . $request->input('q') . '%')
                  ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'qc' => 'required|max:200'
    ]);

    $model = new QCModel;
    $model->qc = strtoupper($request->input('qc'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:qc,id',
      'qc' => 'required|max:200'
    ]);

    $model = QCModel::find($request->input('id'));
    if ($request->has('qc')) $model->qc = strtoupper($request->input('qc'));
    $model->save();
  }
}