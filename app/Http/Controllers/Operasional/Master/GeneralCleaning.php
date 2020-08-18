<?php

namespace App\Http\Controllers\Operasional\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\AreaGeneralCleaning as AreaGeneralCleaningModel;
use App\Http\Models\KegiatanGeneralCleaning as KegiatanGeneralCleaningModel;

class GeneralCleaning extends Controller
{
  public function areaGeneralCleaning(Request $request)
  {
    $this->title('General Cleaning | BangsusSys')->role($request->user()->role->role_code);
    return view('operasional.master.general_cleaning.area_general_cleaning.wrapper', $this->passParams());
  }

  public function kegiatanGeneralCleaning(AreaGeneralCleaningModel $areaGeneralCleaning, Request $request)
  {
    $this->title('General Cleaning | BangsusSys')->role($request->user()->role->role_code);
    return view('operasional.master.general_cleaning.kegiatan_general_cleaning.wrapper', $this->passParams(['areaGeneralCleaning' => $areaGeneralCleaning, 'areaGeneralCleanings' => AreaGeneralCleaningModel::all()]));
  }
}