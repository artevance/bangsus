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

  Route::get('optimize_image/{page}', 'OptimizeImage@index');

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
        Route::get('terotorisasi', 'Cabang@authorized');
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
        Route::get('fillable', 'KelompokFoto@fillable');
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
      Route::prefix('barang')->group(function () {
        Route::get('', 'Barang@index');
        Route::get('{id}', 'Barang@get');
        Route::post('', 'Barang@store');
        Route::put('', 'Barang@amend');
      });
    });
    Route::prefix('karyawan')->group(function () {
      Route::get('', 'Karyawan@index');
      Route::get('{id}', 'Karyawan@get');
      Route::post('', 'Karyawan@store');
      Route::put('', 'Karyawan@amend');
      Route::put('foto_ktp', 'Karyawan@amendFotoKTP');
    });
    Route::prefix('tugas_karyawan')->group(function () {
      Route::get('', 'TugasKaryawan@index');
      Route::get('parent/{id}', 'TugasKaryawan@parent');
      Route::get('cabang', 'TugasKaryawan@branch');
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
      Route::prefix('impor_jadwal')->group(function () {
        Route::post('', 'Absensi\ImporJadwal@store');
        Route::post('preview', 'Absensi\ImporJadwal@preview');
      });
      Route::prefix('impor_absensi')->group(function () {
        Route::post('', 'Absensi\ImporAbsensi@store');
        Route::post('preview', 'Absensi\ImporAbsensi@preview');
      });
    });
    Route::prefix('pengajuan_jadwal_absensi')->group(function () {
      Route::get('', 'PengajuanJadwalAbsensi@index');
      Route::get('{id}', 'PengajuanJadwalAbsensi@get');
      Route::post('', 'PengajuanJadwalAbsensi@store');
      Route::put('', 'PengajuanJadwalAbsensi@amend');
      Route::put('approve', 'PengajuanJadwalAbsensi@approve');
      Route::delete('', 'PengajuanJadwalAbsensi@destroy');
    });
    Route::prefix('form_operasional')->namespace('FormOperasional')->group(function () {
      Route::prefix('form_c1')->namespace('FormC1')->group(function () {
        Route::prefix('form_thawing_ayam')->group(function () {
          Route::get('', 'FormThawingAyam@index');
          Route::get('cabang_harian', 'FormThawingAyam@dailyBranch');
          Route::get('{id}', 'FormThawingAyam@get');
          Route::post('', 'FormThawingAyam@store');
          Route::put('', 'FormThawingAyam@amend');
          Route::delete('', 'FormThawingAyam@destroy');
        });
        Route::prefix('form_goreng')->group(function () {
          Route::get('', 'FormGoreng@index');
          Route::get('cabang_harian', 'FormGoreng@dailyBranch');
          Route::get('{id}', 'FormGoreng@get');
          Route::post('', 'FormGoreng@store');
          Route::put('', 'FormGoreng@amend');
          Route::delete('', 'FormGoreng@destroy');
        });
        Route::prefix('form_masak_nasi')->group(function () {
          Route::get('', 'FormMasakNasi@index');
          Route::get('cabang_harian', 'FormMasakNasi@dailyBranch');
          Route::get('{id}', 'FormMasakNasi@get');
          Route::post('', 'FormMasakNasi@store');
          Route::put('', 'FormMasakNasi@amend');
          Route::delete('', 'FormMasakNasi@destroy');
        });
        Route::prefix('form_sambal')->group(function () {
          Route::get('', 'FormSambal@index');
          Route::get('cabang_harian', 'FormSambal@dailyBranch');
          Route::get('{id}', 'FormSambal@get');
          Route::post('', 'FormSambal@store');
          Route::put('', 'FormSambal@amend');
          Route::delete('', 'FormSambal@destroy');
        });
        Route::prefix('form_tepung')->group(function () {
          Route::get('', 'FormTepung@index');
          Route::get('cabang_harian', 'FormTepung@dailyBranch');
          Route::get('{id}', 'FormTepung@get');
          Route::post('', 'FormTepung@store');
          Route::put('', 'FormTepung@amend');
          Route::delete('', 'FormTepung@destroy');
        });
        Route::prefix('form_minyak')->group(function () {
          Route::get('', 'FormMinyak@index');
          Route::get('cabang_harian', 'FormMinyak@dailyBranch');
          Route::get('{id}', 'FormMinyak@get');
          Route::post('', 'FormMinyak@store');
          Route::put('', 'FormMinyak@amend');
          Route::delete('', 'FormMinyak@destroy');
        });
        Route::prefix('form_margarin')->group(function () {
          Route::get('', 'FormMargarin@index');
          Route::get('cabang_harian', 'FormMargarin@dailyBranch');
          Route::get('{id}', 'FormMargarin@get');
          Route::post('', 'FormMargarin@store');
          Route::put('', 'FormMargarin@amend');
          Route::delete('', 'FormMargarin@destroy');
        });
        Route::prefix('form_lpg')->group(function () {
          Route::get('', 'FormLPG@index');
          Route::get('cabang_harian', 'FormLPG@dailyBranch');
          Route::get('{id}', 'FormLPG@get');
          Route::post('', 'FormLPG@store');
          Route::put('', 'FormLPG@amend');
          Route::delete('', 'FormLPG@destroy');
        });
      });
      Route::prefix('form_c2')->group(function () {
        Route::get('', 'FormC2@index');
        Route::get('cabang_tipe_harian', 'FormC2@dailyBranchType');
        Route::get('{id}', 'FormC2@get');
        Route::post('', 'FormC2@store');
        Route::put('', 'FormC2@amend');
        Route::delete('', 'FormC2@destroy');
      });
      Route::prefix('form_c3')->group(function () {
        Route::get('', 'FormC3@index');
        Route::get('cabang_harian', 'FormC3@dailyBranch');
        Route::get('{id}', 'FormC3@get');
        Route::post('', 'FormC3@store');
        Route::put('', 'FormC3@amend');
        Route::delete('', 'FormC3@destroy');
      });
      Route::prefix('form_c4')->group(function () {
        Route::get('', 'FormC4@index');
        Route::get('cabang_harian', 'FormC4@dailyBranch');
        Route::get('{id}', 'FormC4@get');
        Route::post('', 'FormC4@store');
        Route::put('', 'FormC4@amend');
        Route::delete('', 'FormC4@destroy');
      });
      Route::prefix('form_c5')->group(function () {
        Route::get('', 'FormC5@index');
        Route::get('cabang_tipe_harian', 'FormC5@dailyBranchType');
        Route::get('{id}', 'FormC5@get');
        Route::post('', 'FormC5@store');
        Route::put('', 'FormC5@amend');
        Route::delete('', 'FormC5@destroy');
      });
      Route::prefix('form_foto')->group(function () {
        Route::get('', 'FormFoto@index');
        Route::get('cabang_tipe_harian', 'FormFoto@dailyBranchType');
        Route::get('{id}', 'FormFoto@get');
        Route::post('', 'FormFoto@store');
        Route::put('', 'FormFoto@amend');
        Route::delete('', 'FormFoto@destroy');
      });
      Route::prefix('form_denda_foto')->group(function () {
        Route::post('denda', 'FormDendaFoto@storeDenda');
        Route::post('tidak_denda', 'FormDendaFoto@storeTidakDenda');
        Route::put('', 'FormDendaFoto@amend');
        Route::put('generate', 'FormDendaFoto@generate');
        Route::delete('', 'FormDendaFoto@destroy');
      });
      Route::prefix('form_laporan_foto')->group(function () {
        Route::get('', 'FormLaporanFoto@index');
        Route::get('cabang_tipe_harian', 'FormLaporanFoto@dailyBranchType');
        Route::get('{id}', 'FormLaporanFoto@get');
        Route::post('', 'FormLaporanFoto@store');
        Route::put('', 'FormLaporanFoto@amend');
        Route::delete('', 'FormLaporanFoto@destroy');
      });
      Route::prefix('form_aktivitas_marketing')->group(function () {
        Route::get('', 'FormAktivitasMarketing@index');
        Route::get('cabang_harian', 'FormAktivitasMarketing@dailyBranch');
        Route::get('{id}', 'FormAktivitasMarketing@get');
        Route::post('', 'FormAktivitasMarketing@store');
        Route::put('', 'FormAktivitasMarketing@amend');
        Route::delete('', 'FormAktivitasMarketing@destroy');
      });
      Route::prefix('form_pemberian_tugas')->group(function () {
        Route::get('', 'FormPemberianTugas@index');
        Route::get('active_cabang', 'FormPemberianTugas@activeBranch');
        Route::get('{id}', 'FormPemberianTugas@get');
        Route::post('', 'FormPemberianTugas@store');
        Route::put('', 'FormPemberianTugas@amend');
        Route::delete('', 'FormPemberianTugas@destroy');
      });
      Route::prefix('form_pengumpulan_tugas')->group(function () {
        Route::get('', 'FormPengumpulanTugas@index');
        Route::get('{id}', 'FormPengumpulanTugas@get');
        Route::get('file/{id}', 'FormPengumpulanTugas@getFile');
        Route::post('', 'FormPengumpulanTugas@store');
        Route::put('', 'FormPengumpulanTugas@amend');
        Route::delete('', 'FormPengumpulanTugas@destroy');
      });
      Route::prefix('form_laporan_cabang')->group(function () {
        Route::get('', 'FormLaporanCabang@index');
        Route::get('cabang', 'FormLaporanCabang@branch');
        Route::get('{id}', 'FormLaporanCabang@get');
        Route::get('file/{id}', 'FormLaporanCabang@getFile');
        Route::post('', 'FormLaporanCabang@store');
        Route::put('', 'FormLaporanCabang@amend');
        Route::delete('', 'FormLaporanCabang@destroy');
      });
      Route::prefix('purchase_order')->group(function () {
        Route::get('', 'PurchaseOrder@index');
        Route::get('cabang_harian', 'PurchaseOrder@dailyBranch');
        Route::get('{id}', 'PurchaseOrder@get');
        Route::post('', 'PurchaseOrder@store');
        Route::put('', 'PurchaseOrder@amend');
        Route::put('approve', 'PurchaseOrder@amendApprove');
        Route::delete('', 'PurchaseOrder@destroy');
      });
      Route::prefix('stok_opname')->group(function () {
        Route::get('', 'StokOpname@index');
        Route::get('cabang_harian', 'StokOpname@dailyBranch');
        Route::get('{id}', 'StokOpname@get');
        Route::post('', 'StokOpname@store');
        Route::put('', 'StokOpname@amend');
        Route::put('approve', 'StokOpname@amendApprove');
        Route::delete('', 'StokOpname@destroy');
      });
    });
    Route::prefix('report_center')->namespace('ReportCenter')->group(function () {
      Route::prefix('laporan_absensi')->namespace('LaporanAbsensi')->group(function () {
        Route::prefix('laporan_jadwal')->group(function () {
          Route::get('', 'LaporanJadwal@index');
        });
        Route::prefix('laporan_keterlambatan')->group(function () {
          Route::get('', 'LaporanKeterlambatan@index');
        });
        Route::prefix('laporan_presensi')->group(function () {
          Route::get('', 'LaporanPresensi@index');
        });
        Route::prefix('laporan_kehadiran')->group(function () {
          Route::get('', 'LaporanKehadiran@index');
        });
        Route::prefix('laporan_absensi')->group(function () {
          Route::get('', 'LaporanAbsensi@index');
        });
      });
      Route::prefix('laporan_form_operasional')->namespace('LaporanFormOperasional')->group(function () {
        Route::prefix('laporan_form_c1')->group(function () {
          Route::get('', 'LaporanFormC1@index');
        });
        Route::prefix('laporan_form_c2')->group(function () {
          Route::get('', 'LaporanFormC2@index');
        });
        Route::prefix('laporan_form_c3')->group(function () {
          Route::get('', 'LaporanFormC3@index');
        });
        Route::prefix('laporan_form_c4')->group(function () {
          Route::get('', 'LaporanFormC4@index');
        });
        Route::prefix('laporan_form_c5')->group(function () {
          Route::get('', 'LaporanFormC5@index');
        });
        Route::prefix('laporan_form_aktivitas_marketing')->group(function () {
          Route::get('', 'LaporanFormAktivitasMarketing@index');
        });
        Route::prefix('laporan_form_denda_foto')->group(function () {
          Route::get('', 'LaporanFormDendaFoto@index');
        });
      });
    });
  });
});