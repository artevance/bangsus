<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\SupplierMutation as SupplierMutationModel;
use App\Http\Models\SupplierMutationD;
use App\Http\Models\Barang;
use App\Http\Models\Cabang;
use App\Http\Models\Gambar;

use Intervention\Image\Facades\Image;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

use Carbon\Carbon;

class SupplierMutation extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(SupplierMutationModel::with([
        'cabang',
        'd'
      ]))
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if (is_null(SupplierMutationModel::find($id))) return $this->response(404);

    return $this->data(SupplierMutationModel::with([
      'cabang',
      'd',
      'tugas_karyawan',
      'tugas_karyawan.karyawan',
    ])->find($id))->response(200);
  }

  public function report(Request $request, $id)
  {
    if (is_null(SupplierMutationModel::find($id))) return $this->response(404);

    $supplierMutation = SupplierMutationModel::with([
      'cabang',
      'd'
    ])->find($id);

    $spreadsheet = new Spreadsheet;
    $sheet = $spreadsheet->getActiveSheet();

    $container = [
      ['Terima Barang Supplier'],
      ['Cabang', $supplierMutation->cabang->kode_cabang . ' - ' . $supplierMutation->cabang->cabang],
      ['Supplier', $supplierMutation->supplier_mutasi->supplier_mutasi],
      ['Penerima', $supplierMutation->karyawan->nama_karyawan],
      [$supplierMutation->tanggal_form],
      [],
      ['Kode Barang', 'Nama Barang', 'Qty', 'Satuan', 'Keterangan'],
    ];

    foreach ($supplierMutation->d as $detail) {
      switch ($detail->level_satuan) {
        case 1 :
          $satuan = $detail->barang->satuan->satuan;
          break;
        case 2 :
          $satuan = $detail->barang->satuan_dua->satuan;
          break;
        case 3 :
          $satuan = $detail->barang->satuan_tiga->satuan;
          break;
        case 4 :
          $satuan = $detail->barang->satuan_empat->satuan;
          break;
        case 5 :
          $satuan = $detail->barang->satuan_lima->satuan;
          break;
      }

      $container[] = [
        $detail->barang->kode_barang, $detail->barang->nama_barang, $detail->qty, $satuan, $detail->keterangan
      ];
    }

    $sheet->fromArray(
        $container,
        null,
        'A1'
      );

    $filename = 'Mutasi Supplier - ' . uniqid() . '.xlsx';
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save($filename);

    return response()
        ->download($filename)->deleteFileAfterSend();
  }

  public function daily(Request $request)
  {
    $query = [
      'tanggal_form' => $request->input('tanggal_form', date('Y-m-d'))
    ];

    return $this
      ->data(
        SupplierMutationModel::with([
          'cabang',
          'supplier_mutasi',
          'd'
        ])
        ->where('tanggal_form', $query['tanggal_form'])
        ->orderBy('jam')
        ->get()
      )->response(200);
  }

  public function dailyBranch(Request $request)
  {
    $query = [
      'cabang_id' => $request->input('cabang_id', Cabang::first()->id),
      'tanggal_form' => $request->input('tanggal_form', date('Y-m-d'))
    ];

    return $this
      ->data(
        SupplierMutationModel::with([
          'cabang',
          'supplier_mutasi',
          'd'
        ])
        ->where('tanggal_form', $query['tanggal_form'])
        ->byCabang($query['cabang_id'])
        ->orderBy('jam')
        ->get()
      )->response(200);
  }

  public function image(Request $request, $id)
  {
    $response = $request->has('greyscale')
      ? Image::make(SupplierMutationD::find($id)->dir_gambar)->greyscale()->response('jpeg')
      : Image::make(SupplierMutationD::find($id)->dir_gambar)->response('jpeg');

    return $response;
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'supplier_mutasi_id',
      'cabang_id',
      'cabang_asal_id',
      'tugas_karyawan_id',
      'd'
    ), [
      'supplier_mutasi_id' => 'required|exists:supplier_mutasi,id',
      'cabang_id' => 'required|exists:cabang,id',
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'd.*.barang_id' => 'required|exists:barang,id',
      'd.*.qty' => 'required|numeric|gt:0|max:10000000000',
      'd.*.level_satuan' => 'required',
      'd.*.harga_barang' => 'required|max:10000000000',
      'd.*.keterangan' => 'nullable|max:200',
      'd.*.gambar' => 'required'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $supplierMutationModel = new SupplierMutationModel;
    $supplierMutationModel->tanggal_form = date('Y-m-d');
    $supplierMutationModel->jam = date('H:i:s');
    $supplierMutationModel->supplier_mutasi_id = $request->input('supplier_mutasi_id');
    $supplierMutationModel->cabang_id = $request->input('cabang_id');
    $supplierMutationModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $supplierMutationModel->approve = false;
    $supplierMutationModel->user_id = $request->user()->id;
    $supplierMutationModel->save();

    foreach ($request->input('d') as $d) {
      $dir = public_path('img/supplier_mutation/' . uniqid() . uniqid() . uniqid() . '.jpg');
      Image::make(file_get_contents($d['gambar']))->save($dir);

      $barang = Barang::find($d['barang_id']);
      $constant = 1;

      for ($i = 1; $i <= $d['level_satuan']; $i++) {
        switch ($i) {
          case 1 :
            $constant = $constant;
            break;
          case 2 :
            $constant *= $barang->rasio_dua;
            break;
          case 3 :
            $constant *= $barang->rasio_tiga;
            break;
          case 4 :
            $constant *= $barang->rasio_empat;
            break;
          case 5 :
            $constant *= $barang->rasio_lima;
            break;
        }
      }

      $detailModel = new SupplierMutationD;
      $detailModel->supplier_mutation_id = $supplierMutationModel->id;
      $detailModel->dir_gambar = $dir;
      $detailModel->barang_id = $d['barang_id'];
      $detailModel->qty = $d['qty'];
      $detailModel->level_satuan = $d['level_satuan'];
      $detailModel->qty_konversi = $d['qty'] * $constant;
      $detailModel->qty_kg = 0;
      $detailModel->harga_barang = $d['harga_barang'];
      $detailModel->keterangan = $d['keterangan'] ?? '';
      $detailModel->save();
    }
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'supplier_mutasi_id',
      'tugas_karyawan_id',
      'd'
    ), [
      'id' => 'required|exists:supplier_mutation,id',
      'supplier_mutasi_id' => 'required|exists:supplier_mutasi,id',
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'd.*.barang_id' => 'required|exists:barang,id',
      'd.*.qty' => 'required|numeric|gt:0|max:10000000000',
      'd.*.level_satuan' => 'required',
      'd.*.harga_barang' => 'required|max:10000000000',
      'd.*.keterangan' => 'nullable|max:200',
      'd.*.gambar' => 'required'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $supplierMutationModel = SupplierMutationModel::find($request->input('id'));
    $supplierMutationModel->supplier_mutasi_id = $request->input('supplier_mutasi_id');
    $supplierMutationModel->approve = false;
    $supplierMutationModel->user_id = $request->user()->id;
    $supplierMutationModel->save();

    $supplierMutationModel->d->each(
      function ($d) {
        $d->delete();
      }
    );

    foreach ($request->input('d') as $d) {
      $dir = public_path('img/supplier_mutation/' . uniqid() . uniqid() . uniqid() . '.jpg');
      Image::make(file_get_contents($d['gambar']))->save($dir);

      $barang = Barang::find($d['barang_id']);
      $constant = 1;

      for ($i = 1; $i <= $d['level_satuan']; $i++) {
        switch ($i) {
          case 1 :
            $constant = $constant;
            break;
          case 2 :
            $constant *= $barang->rasio_dua;
            break;
          case 3 :
            $constant *= $barang->rasio_tiga;
            break;
          case 4 :
            $constant *= $barang->rasio_empat;
            break;
          case 5 :
            $constant *= $barang->rasio_lima;
            break;
        }
      }

      $satuanId = null;
      switch ($d['level_satuan']) {
        case 1 :
          $satuanId = $barang->satuan_id;
          break;
        case 2 :
          $satuanId = $barang->satuan_dua_id;
          break;
        case 3 :
          $satuanId = $barang->satuan_tiga_id;
          break;
        case 4 :
          $satuanId = $barang->satuan_empat_id;
          break;
        case 5 :
          $satuanId = $barang->satuan_lima_id;
          break;
      }

      $detailModel = new SupplierMutationD;
      $detailModel->supplier_mutation_id = $supplierMutationModel->id;
      $detailModel->dir_gambar = $dir;
      $detailModel->barang_id = $d['barang_id'];
      $detailModel->qty = $d['qty'];
      $detailModel->level_satuan = $d['level_satuan'];
      $detailModel->qty_konversi = $d['qty'] * $constant;
      $detailModel->qty_kg = 0;
      $detailModel->harga_barang = $d['harga_barang'];
      $detailModel->keterangan = $d['keterangan'] ?? '';
      $detailModel->save();
    }
  }

  public function amendApprove(Request $request)
  {
    $v = Validator::make($request->only('id'), [
      'id' => 'required|exists:supplier_mutation,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $supplierMutationModel = SupplierMutationModel::find($request->input('id'));
    $supplierMutationModel->approve = true;
    $supplierMutationModel->user_id = $request->user()->id;
    $supplierMutationModel->save();
  }

  public function destroy(Request $request)
  {
    $v = Validator::make($request->only('id'), [
      'id' => 'required|exists:supplier_mutation,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $supplierMutationModel = SupplierMutationModel::find($request->input('id'));

    $supplierMutationModel->d->each(
      function ($d) {
        $d->delete();
      }
    );

    $supplierMutationModel->user_id = $request->user()->id;
    $supplierMutationModel->save();
    $supplierMutationModel->delete();
  }
}