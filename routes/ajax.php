<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Ajax Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Ajax routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "ajax" middleware group. Enjoy building your Ajax!
|
*/

Route::prefix('v1')->namespace('v1')->group(function () {
  Route::post('login', 'Login@index');
  Route::get('robust', 'Robust@index');

  Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout', 'Logout@index');

    Route::prefix('master')->namespace('Master')->group(function () {
      Route::prefix('tipe_kontak')->group(function () {
        Route::get('', 'TipeKontak@index');
        Route::get('{id}', 'TipeKontak@get');
        Route::post('', 'TipeKontak@store');
        Route::put('', 'TipeKontak@amend');
      });
      Route::prefix('tipe_alamat')->group(function () {
        Route::get('', 'TipeAlamat@index');
        Route::get('{id}', 'TipeAlamat@get');
        Route::post('', 'TipeAlamat@store');
        Route::put('', 'TipeAlamat@amend');
      });
      Route::prefix('tipe_foto_karyawan')->group(function () {
        Route::get('', 'TipeFotoKaryawan@index');
        Route::get('{id}', 'TipeFotoKaryawan@get');
        Route::post('', 'TipeFotoKaryawan@store');
        Route::put('', 'TipeFotoKaryawan@amend');
      });
      Route::prefix('jabatan')->group(function () {
        Route::get('', 'Jabatan@index');
        Route::get('{id}', 'Jabatan@get');
        Route::post('', 'Jabatan@store');
        Route::put('', 'Jabatan@amend');
      });
      Route::prefix('divisi')->group(function () {
        Route::get('', 'Divisi@index');
        Route::get('{id}', 'Divisi@get');
        Route::post('', 'Divisi@store');
        Route::put('', 'Divisi@amend');
      });
      Route::prefix('tipe_cabang')->group(function () {
        Route::get('', 'TipeCabang@index');
        Route::get('{id}', 'TipeCabang@get');
        Route::post('', 'TipeCabang@store');
        Route::put('', 'TipeCabang@amend');
      });
      Route::prefix('cabang')->group(function () {
        Route::get('', 'Cabang@index');
        Route::get('{id}', 'Cabang@get');
        Route::post('', 'Cabang@store');
        Route::put('', 'Cabang@amend');
      });
      Route::prefix('tipe_absensi')->group(function () {
        Route::get('', 'TipeAbsensi@index');
        Route::get('{id}', 'TipeAbsensi@get');
        Route::post('', 'TipeAbsensi@store');
        Route::put('', 'TipeAbsensi@amend');
      });
      Route::prefix('golongan_darah')->group(function () {
        Route::get('', 'GolonganDarah@index');
        Route::get('{id}', 'GolonganDarah@get');
        Route::post('', 'GolonganDarah@store');
        Route::put('', 'GolonganDarah@amend');
      });
      Route::prefix('jenis_kelamin')->group(function () {
        Route::get('', 'JenisKelamin@index');
        Route::get('{id}', 'JenisKelamin@get');
        Route::post('', 'JenisKelamin@store');
        Route::put('', 'JenisKelamin@amend');
      });
      Route::prefix('satuan')->group(function () {
        Route::get('', 'Satuan@index');
        Route::get('{id}', 'Satuan@get');
        Route::post('', 'Satuan@store');
        Route::put('', 'Satuan@amend');
      });
      Route::prefix('supplier')->group(function () {
        Route::get('', 'Supplier@index');
        Route::get('{id}', 'Supplier@get');
        Route::post('', 'Supplier@store');
        Route::put('', 'Supplier@amend');
      });
      Route::prefix('item_goreng')->group(function () {
        Route::get('', 'ItemGoreng@index');
        Route::get('{id}', 'ItemGoreng@get');
        Route::post('', 'ItemGoreng@store');
        Route::put('', 'ItemGoreng@amend');
      });
      Route::prefix('tipe_proses_sambal')->group(function () {
        Route::get('', 'TipeProsesSambal@index');
        Route::get('{id}', 'TipeProsesSambal@get');
        Route::post('', 'TipeProsesSambal@store');
        Route::put('', 'TipeProsesSambal@amend');
      });
      Route::prefix('tipe_proses_tepung')->group(function () {
        Route::get('', 'TipeProsesTepung@index');
        Route::get('{id}', 'TipeProsesTepung@get');
        Route::post('', 'TipeProsesTepung@store');
        Route::put('', 'TipeProsesTepung@amend');
      });
      Route::prefix('tipe_proses_minyak')->group(function () {
        Route::get('', 'TipeProsesMinyak@index');
        Route::get('{id}', 'TipeProsesMinyak@get');
        Route::post('', 'TipeProsesMinyak@store');
        Route::put('', 'TipeProsesMinyak@amend');
      });
      Route::prefix('tipe_proses_margarin')->group(function () {
        Route::get('', 'TipeProsesMargarin@index');
        Route::get('{id}', 'TipeProsesMargarin@get');
        Route::post('', 'TipeProsesMargarin@store');
        Route::put('', 'TipeProsesMargarin@amend');
      });
      Route::prefix('tipe_proses_lpg')->group(function () {
        Route::get('', 'TipeProsesLPG@index');
        Route::get('{id}', 'TipeProsesLPG@get');
        Route::post('', 'TipeProsesLPG@store');
        Route::put('', 'TipeProsesLPG@amend');
      });
      Route::prefix('quality_control')->group(function () {
        Route::get('', 'QualityControl@index');
        Route::get('{id}', 'QualityControl@get');
        Route::post('', 'QualityControl@store');
        Route::put('', 'QualityControl@amend');
      });
      Route::prefix('parameter_quality_control')->group(function () {
        Route::get('', 'ParameterQualityControl@index');
        Route::get('parent/{id}', 'ParameterQualityControl@parent');
        Route::get('{id}', 'ParameterQualityControl@get');
        Route::post('', 'ParameterQualityControl@store');
        Route::put('', 'ParameterQualityControl@amend');
      });
      Route::prefix('opsi_parameter_quality_control')->group(function () {
        Route::get('', 'OpsiParameterQualityControl@index');
        Route::get('parent/{id}', 'OpsiParameterQualityControl@parent');
        Route::get('{id}', 'OpsiParameterQualityControl@get');
        Route::post('', 'OpsiParameterQualityControl@store');
        Route::put('', 'OpsiParameterQualityControl@amend');
      });
      Route::prefix('aktivitas_karyawan')->group(function () {
        Route::get('', 'AktivitasKaryawan@index');
        Route::get('{id}', 'AktivitasKaryawan@get');
        Route::post('', 'AktivitasKaryawan@store');
        Route::put('', 'AktivitasKaryawan@amend');
      });
      Route::prefix('atribut_karyawan')->group(function () {
        Route::get('', 'AtributKaryawan@index');
        Route::get('{id}', 'AtributKaryawan@get');
        Route::post('', 'AtributKaryawan@store');
        Route::put('', 'AtributKaryawan@amend');
      });
      Route::prefix('parameter_atribut_karyawan')->group(function () {
        Route::get('', 'ParameterAtributKaryawan@index');
        Route::get('parent/{id}', 'ParameterAtributKaryawan@parent');
        Route::get('{id}', 'ParameterAtributKaryawan@get');
        Route::post('', 'ParameterAtributKaryawan@store');
        Route::put('', 'ParameterAtributKaryawan@amend');
      });
      Route::prefix('kegiatan_kebersihan')->group(function () {
        Route::get('', 'KegiatanKebersihan@index');
        Route::get('{id}', 'KegiatanKebersihan@get');
        Route::post('', 'KegiatanKebersihan@store');
        Route::put('', 'KegiatanKebersihan@amend');
      });
      Route::prefix('area_general_cleaning')->group(function () {
        Route::get('', 'AreaGeneralCleaning@index');
        Route::get('{id}', 'AreaGeneralCleaning@get');
        Route::post('', 'AreaGeneralCleaning@store');
        Route::put('', 'AreaGeneralCleaning@amend');
      });
      Route::prefix('kegiatan_general_cleaning')->group(function () {
        Route::get('', 'KegiatanGeneralCleaning@index');
        Route::get('parent/{id}', 'KegiatanGeneralCleaning@parent');
        Route::get('{id}', 'KegiatanGeneralCleaning@get');
        Route::post('', 'KegiatanGeneralCleaning@store');
        Route::put('', 'KegiatanGeneralCleaning@amend');
      });
      Route::prefix('kelompok_foto')->group(function () {
        Route::get('', 'KelompokFoto@index');
        Route::get('{id}', 'KelompokFoto@get');
        Route::post('', 'KelompokFoto@store');
        Route::put('', 'KelompokFoto@amend');
      });
      Route::prefix('denda_foto')->group(function () {
        Route::get('', 'DendaFoto@index');
        Route::get('parent/{id}', 'DendaFoto@parent');
        Route::get('{id}', 'DendaFoto@get');
        Route::post('', 'DendaFoto@store');
        Route::put('', 'DendaFoto@amend');
      });
      Route::prefix('kelompok_laporan_foto')->group(function () {
        Route::get('', 'KelompokLaporanFoto@index');
        Route::get('{id}', 'KelompokLaporanFoto@get');
        Route::post('', 'KelompokLaporanFoto@store');
        Route::put('', 'KelompokLaporanFoto@amend');
      });
      Route::prefix('aktivitas_marketing')->group(function () {
        Route::get('', 'AktivitasMarketing@index');
        Route::get('{id}', 'AktivitasMarketing@get');
        Route::post('', 'AktivitasMarketing@store');
        Route::put('', 'AktivitasMarketing@amend');
      });
      Route::prefix('item_marketing')->group(function () {
        Route::get('', 'ItemMarketing@index');
        Route::get('{id}', 'ItemMarketing@get');
        Route::post('', 'ItemMarketing@store');
        Route::put('', 'ItemMarketing@amend');
      });
    });
    Route::prefix('karyawan')->group(function () {
      Route::get('', 'Karyawan@index');
      Route::get('{id}', 'Karyawan@get');
      Route::post('', 'Karyawan@store');
      Route::put('', 'Karyawan@amend');
    });
    Route::prefix('tugas_karyawan')->group(function () {
      Route::get('', 'TugasKaryawan@index');
      Route::get('parent/{id}', 'TugasKaryawan@parent');
      Route::get('{id}', 'TugasKaryawan@get');
      Route::post('', 'TugasKaryawan@store');
      Route::put('', 'TugasKaryawan@amend');
    });
    Route::prefix('absensi')->group(function () {
      Route::get('', 'Absensi@index');
      Route::get('manual', 'Absensi@manual');
      Route::get('{id}', 'Absensi@get');
      Route::post('', 'Absensi@store');
      Route::put('', 'Absensi@amend');
      Route::delete('', 'Absensi@destroy');
    });
    Route::prefix('pengajuan_jadwal_absensi')->group(function () {
      Route::get('', 'PengajuanJadwalAbsensi@index');
      Route::get('{id}', 'PengajuanJadwalAbsensi@get');
      Route::post('', 'PengajuanJadwalAbsensi@store');
      Route::put('', 'PengajuanJadwalAbsensi@amend');
      Route::put('approve', 'PengajuanJadwalAbsensi@approve');
      Route::delete('', 'PengajuanJadwalAbsensi@destroy');
    });
  });
});