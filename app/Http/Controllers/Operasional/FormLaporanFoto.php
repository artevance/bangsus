<?php

namespace App\Http\Controllers\Operasional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\FormLaporanFoto as FormLaporanFotoModel;
use App\Http\Models\Gambar as GambarModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class FormLaporanFoto extends Controller
{
  public function index(Request $request)
  {
    switch ($request->user()->role->role_code) {
      case 'admin' :
        $query = [
          'cabang_id' => $request->query('cabang_id', 1),
          'tanggal_form' => $request->query('tanggal_form', date('Y-m-d'))
        ];
      break;
      case 'leader' :
        $query = [
          'cabang_id' => $request->user()->tugas_karyawan->cabang_id,
          'tanggal_form' => date('Y-m-d')
        ];
      break;
      default :
    }

    $this->title('Form Laporan Foto | BangsusSys')
      ->role($request->user()->role->role_code)
      ->query($query);
    return view('operasional.form_laporan_foto.wrapper', 
      $this->passParams([
        'cabangs' => CabangModel::all()
      ]
    ));
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_laporan_foto,id'
    ]);

    return ['data' => FormLaporanFotoModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' =>
        FormLaporanFotoModel::with(['tugas_karyawan', 'user'])
          ->get()
    ];
  }

  public function cabangHarian(Request $request)
  {
    $request->validate([
      'cabang_id' => 'required|exists:cabang,id',
      'tanggal_form' => 'required|date',
    ]);

    return [
      'data' => 
        FormLaporanFotoModel::with([
            'tugas_karyawan',
              'tugas_karyawan.cabang',
                'tugas_karyawan.cabang.tipe_cabang',
              'tugas_karyawan.divisi',
              'tugas_karyawan.jabatan',
              'tugas_karyawan.karyawan',
                'tugas_karyawan.karyawan.golongan_darah',
                'tugas_karyawan.karyawan.jenis_kelamin',
            'kelompok_laporan_foto',
            'user'
          ])
          ->whereHas('tugas_karyawan', function ($query) use ($request) {
            $query->where('cabang_id', '=', $request->input('cabang_id'));
          })
          ->where(function ($query) use ($request) {
            $query->where('cabang_id', $request->input('cabang_id'))
              ->orWhere('cabang_id', null);
          })
          ->where('tanggal_form', $request->input('tanggal_form'))
          ->get()
    ];
  }

  public function cabangKelompokHarian(Request $request)
  {
    $request->validate([
      'cabang_id' => 'required|exists:cabang,id',
      'tanggal_form' => 'required|date',
      'kelompok_laporan_foto_id' => 'required|exists:kelompok_laporan_foto,id',
    ]);

    return [
      'data' => 
        FormLaporanFotoModel::with([
            'tugas_karyawan',
              'tugas_karyawan.cabang',
                'tugas_karyawan.cabang.tipe_cabang',
              'tugas_karyawan.divisi',
              'tugas_karyawan.jabatan',
              'tugas_karyawan.karyawan',
                'tugas_karyawan.karyawan.golongan_darah',
                'tugas_karyawan.karyawan.jenis_kelamin',
            'kelompok_laporan_foto',
            'user',
            'cabang'
          ])
          ->where(function ($query) use ($request) {
            $query->whereHas('tugas_karyawan', function ($q) use ($request) {
              $q->where('cabang_id', '=', $request->input('cabang_id'));
            });
            $query->orWhereHas('cabang', function ($q) use ($request) {
              $q->where('cabang_id', $request->input('cabang_id'))
                ->orWhere('cabang_id', null);
            });
          })
          ->where('kelompok_laporan_foto_id', $request->input('kelompok_laporan_foto_id'))
          ->where('tanggal_form', $request->input('tanggal_form'))
          ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'kelompok_laporan_foto_id' => [
        'required',
        'exists:kelompok_laporan_foto,id'
      ],
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id',
      'gambar' => 'required'
    ]);

    $img = Image::make($request->file('gambar'));

    if ($img->height() > 1000 || $img->width() > 1000)
      if ($img->height() > $img->width())
        $img->resize(null, 500, function ($c) {
          $c->aspectRatio();
        });
      else
        $img->resize(700, null, function ($c) {
          $c->aspectRatio();
        });

    $gambarModel = new GambarModel;
    $gambarModel->konten = $img->encode('jpg', 70);
    $gambarModel->save();

    $formLaporanFotoModel = new FormLaporanFotoModel;
    $formLaporanFotoModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formLaporanFotoModel->tanggal_form = $request->input('tanggal_form');
    $formLaporanFotoModel->jam = $request->input('jam');
    $formLaporanFotoModel->kelompok_laporan_foto_id = $request->input('kelompok_laporan_foto_id');
    $formLaporanFotoModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formLaporanFotoModel->gambar_id = $gambarModel->id;
    $formLaporanFotoModel->tidak_kirim = 0;
    $formLaporanFotoModel->user_id = $request->input('user_id');
    $formLaporanFotoModel->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_foto,id',
      'tugas_karyawan_id' => 'nullable|exists:tugas_karyawan,id',
      'tanggal_form' => 'nullable|date_format:Y-m-d',
      'jam' => 'nullable',
      'kelompok_laporan_foto_id' => [
        'nullable',
        'exists:kelompok_laporan_foto,id'
      ],
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id',
      'gambar' => 'nullable'
    ]);

    if ($request->filled('gambar')) {
      $file = $request->file('gambar');

      $gambarModel = new GambarModel;
      $gambarModel->konten = $file->openFile()->fread($file->getSize());
      $gambarModel->save();
    }

    $formLaporanFotoModel = FormLaporanFotoModel::find($request->input('id'));
    if ($request->has('tugas_karyawan_id')) $formLaporanFotoModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    if ($request->has('tanggal_form')) $formLaporanFotoModel->tanggal_form = $request->input('tanggal_form');
    if ($request->has('jam')) $formLaporanFotoModel->jam = $request->input('jam');
    if ($request->has('kelompok_laporan_foto_id')) $formLaporanFotoModel->kelompok_laporan_foto_id = $request->input('kelompok_laporan_foto_id');
    if ($request->has('keterangan')) $formLaporanFotoModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    if ($request->filled('gambar')) $formLaporanFotoModel->gambar_id = $gambarModel->id;
    $formLaporanFotoModel->user_id = $request->input('user_id');
    $formLaporanFotoModel->save();
  }

  public function delete(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_foto,id',
      'user_id' => 'required|exists:user,id'
    ]);

    $formLaporanFotoModel = FormLaporanFotoModel::find($request->input('id'));
    $formLaporanFotoModel->user_id = $request->input('user_id');
    $formLaporanFotoModel->save();
    $formLaporanFotoModel->delete();
  }
}