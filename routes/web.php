<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/laravel', function () {
  return view('welcome');
});
Route::get('/sandbox', 'Sandbox@index');

Route::permanentRedirect('/', '/dashboard');

Route::middleware('check.not.logged.in')->group(function () {  
  Route::redirect('/', '/home');
  Route::get('/login', 'Auth\Login@index')->name('login');
  Route::post('/authenticate', 'Auth\Authenticate@index');
});

Route::middleware('auth')->group(function () {
  Route::get('/logout', 'Auth\Logout@index');
  Route::get('/dashboard', 'Dashboard@index');
  Route::get('/gambar/{gambar}', 'Gambar@index');

  ROute::get('/migrasi', 'Migrasi@index');

  Route::prefix('/hrd')->group(function () {
    Route::namespace('HRD')->group(function () {

      Route::prefix('/master')->group(function () {
        Route::namespace('Master')->group(function () {
          Route::prefix('/tipe_kontak')->group(function () {
            Route::get('', 'TipeKontak@index')->middleware('role:admin');
            Route::get('/get', 'TipeKontak@get')->middleware('ajax.only');
            Route::get('/search', 'TipeKontak@search')->middleware('ajax.only');
            Route::post('/post', 'TipeKontak@post')->middleware('ajax.only');
            Route::put('/put', 'TipeKontak@put')->middleware('ajax.only');
          });
          Route::prefix('/tipe_alamat')->group(function () {
            Route::get('', 'TipeAlamat@index')->middleware('role:admin');
            Route::get('/get', 'TipeAlamat@get')->middleware('ajax.only');
            Route::get('/search', 'TipeAlamat@search')->middleware('ajax.only');
            Route::post('/post', 'TipeAlamat@post')->middleware('ajax.only');
            Route::put('/put', 'TipeAlamat@put')->middleware('ajax.only');
          });
          Route::prefix('/tipe_foto_karyawan')->group(function () {
            Route::get('', 'TipeFotoKaryawan@index')->middleware('role:admin');
            Route::get('/get', 'TipeFotoKaryawan@get')->middleware('ajax.only');
            Route::get('/search', 'TipeFotoKaryawan@search')->middleware('ajax.only');
            Route::post('/post', 'TipeFotoKaryawan@post')->middleware('ajax.only');
            Route::put('/put', 'TipeFotoKaryawan@put')->middleware('ajax.only');
          });
          Route::prefix('/jabatan')->group(function () {
            Route::get('', 'Jabatan@index')->middleware('role:admin');
            Route::get('/get', 'Jabatan@get')->middleware('ajax.only');
            Route::get('/search', 'Jabatan@search')->middleware('ajax.only');
            Route::post('/post', 'Jabatan@post')->middleware('ajax.only');
            Route::put('/put', 'Jabatan@put')->middleware('ajax.only');
          });
          Route::prefix('/divisi')->group(function () {
            Route::get('', 'Divisi@index')->middleware('role:admin');
            Route::get('/get', 'Divisi@get')->middleware('ajax.only');
            Route::get('/search', 'Divisi@search')->middleware('ajax.only');
            Route::post('/post', 'Divisi@post')->middleware('ajax.only');
            Route::put('/put', 'Divisi@put')->middleware('ajax.only');
          });
          Route::prefix('/tipe_cabang')->group(function () {
            Route::get('', 'TipeCabang@index')->middleware('role:admin');
            Route::get('/get', 'TipeCabang@get')->middleware('ajax.only');
            Route::get('/search', 'TipeCabang@search')->middleware('ajax.only');
            Route::post('/post', 'TipeCabang@post')->middleware('ajax.only');
            Route::put('/put', 'TipeCabang@put')->middleware('ajax.only');
          });
          Route::prefix('/cabang')->group(function () {
            Route::get('', 'Cabang@index')->middleware('role:admin');
            Route::get('/get', 'Cabang@get')->middleware('ajax.only');
            Route::get('/search', 'Cabang@search')->middleware('ajax.only');
            Route::post('/post', 'Cabang@post')->middleware('ajax.only');
            Route::put('/put', 'Cabang@put')->middleware('ajax.only');
          });
          Route::prefix('/jenis_kelamin')->group(function () {
            Route::get('', 'JenisKelamin@index')->middleware('role:admin');
            Route::get('/get', 'JenisKelamin@get')->middleware('ajax.only');
            Route::get('/search', 'JenisKelamin@search')->middleware('ajax.only');
          });
          Route::prefix('/golongan_darah')->group(function () {
            Route::get('', 'GolonganDarah@index')->middleware('role:admin');
            Route::get('/get', 'GolonganDarah@get')->middleware('ajax.only');
            Route::get('/search', 'GolonganDarah@search')->middleware('ajax.only');
          });
          Route::prefix('/tipe_absensi')->group(function () {
            Route::get('', 'TipeAbsensi@index')->middleware('role:admin');
            Route::get('/get', 'TipeAbsensi@get')->middleware('ajax.only');
            Route::get('/search', 'TipeAbsensi@search')->middleware('ajax.only');
            Route::post('/post', 'TipeAbsensi@post')->middleware('ajax.only');
            Route::put('/put', 'TipeAbsensi@put')->middleware('ajax.only');
          });
        });
      });

      Route::prefix('/karyawan')->group(function () {
        Route::get('', 'Karyawan@index')->middleware('role:admin');
        Route::get('/get', 'Karyawan@get')->middleware('ajax.only');
        Route::get('/search', 'Karyawan@search')->middleware('ajax.only');
        Route::post('/post', 'Karyawan@post')->middleware('ajax.only');
        Route::put('/put', 'Karyawan@put')->middleware('ajax.only');
      });
      Route::prefix('/tugas_karyawan')->group(function () {
        Route::get('/karyawan/{karyawan}', 'TugasKaryawan@karyawan');
        Route::get('/get', 'TugasKaryawan@get')->middleware('ajax.only');
        Route::get('/search', 'TugasKaryawan@search')->middleware('ajax.only');
        Route::get('/cabang_harian', 'TugasKaryawan@cabangHarian');
        Route::post('/post', 'TugasKaryawan@post')->middleware('ajax.only');
        Route::put('/put', 'TugasKaryawan@put')->middleware('ajax.only');
      });

      Route::prefix('/absensi')->group(function () {

        Route::get('/get', 'Absensi@get')->middleware('ajax.only');
        Route::get('/search', 'Absensi@search')->middleware('ajax.only');
        Route::post('/post', 'Absensi@post')->middleware('ajax.only');
        Route::put('/put', 'Absensi@put')->middleware('ajax.only');
        Route::delete('/delete', 'Absensi@delete')->middleware('ajax.only');
        Route::namespace('Absensi')->group(function () {
          Route::prefix('/manual')->group(function () {
            Route::get('', 'Manual@index');
            Route::get('/cabang_tipe_harian', 'Manual@cabangTipeHarian');
          });
          Route::prefix('/pengajuan_jadwal_absensi')->group(function () {
            Route::get('', 'PengajuanJadwalAbsensi@index');
            Route::get('/get', 'PengajuanJadwalAbsensi@get');
            Route::get('/search', 'PengajuanJadwalAbsensi@search');
            Route::get('/cabang_tipe_harian', 'PengajuanJadwalAbsensi@cabangTipeHarian');
            Route::post('/post', 'PengajuanJadwalAbsensi@post');
            Route::put('/put', 'PengajuanJadwalAbsensi@put');
            Route::put('/approve', 'PengajuanJadwalAbsensi@approve');
            Route::delete('/delete', 'PengajuanJadwalAbsensi@delete');
          });
          Route::prefix('/impor_jadwal')->group(function () {
            Route::get('', 'ImporJadwal@index');
            Route::post('/impor', 'ImporJadwal@impor');
          });
          Route::prefix('/impor_absensi')->group(function () {
            Route::get('', 'ImporAbsensi@index');
            Route::post('/impor', 'ImporAbsensi@impor');
          });
          Route::prefix('/laporan_jadwal')->group(function () {
            Route::get('', 'LaporanJadwal@index')->middleware('role:admin');
          });
          Route::prefix('/laporan_absensi')->group(function () {
            Route::get('', 'LaporanAbsensi@index')->middleware('role:admin');
          });
          Route::prefix('/laporan_keterlambatan')->group(function () {
            Route::get('', 'LaporanKeterlambatan@index')->middleware('role:admin');
          });
          Route::prefix('/laporan_log_absen')->group(function () {
            Route::get('', 'LaporanLogAbsen@index')->middleware('role:admin');
          });
        });
      });
    });
  });

  Route::prefix('/operasional')->group(function () {
    Route::namespace('Operasional')->group(function () {

      Route::prefix('/master')->group(function () {
        Route::namespace('Master')->group(function () {
          Route::prefix('/satuan')->group(function () {
            Route::get('', 'Satuan@index')->middleware('role:admin');
            Route::get('/get', 'Satuan@get')->middleware('ajax.only');
            Route::get('/search', 'Satuan@search')->middleware('ajax.only');
            Route::post('/post', 'Satuan@post')->middleware('ajax.only');
            Route::put('/put', 'Satuan@put')->middleware('ajax.only');
          });
          Route::prefix('/supplier')->group(function () {
            Route::get('', 'Supplier@index')->middleware('role:admin');
            Route::get('/get', 'Supplier@get')->middleware('ajax.only');
            Route::get('/search', 'Supplier@search')->middleware('ajax.only');
            Route::post('/post', 'Supplier@post')->middleware('ajax.only');
            Route::put('/put', 'Supplier@put')->middleware('ajax.only');
          });
          Route::prefix('/item_goreng')->group(function () {
            Route::get('', 'ItemGoreng@index')->middleware('role:admin');
            Route::get('/get', 'ItemGoreng@get')->middleware('ajax.only');
            Route::get('/search', 'ItemGoreng@search')->middleware('ajax.only');
            Route::post('/post', 'ItemGoreng@post')->middleware('ajax.only');
            Route::put('/put', 'ItemGoreng@put')->middleware('ajax.only');
          });
          Route::prefix('/tipe_proses_produksi')->group(function () {
            Route::namespace('TipeProsesProduksi')->group(function () {
              Route::redirect('', '/operasional/master/tipe_proses_produksi/sambal');
              Route::prefix('/sambal')->group(function () {
                Route::get('', 'Sambal@index')->middleware('role:admin');
                Route::get('/get', 'Sambal@get')->middleware('ajax.only');
                Route::get('/search', 'Sambal@search')->middleware('ajax.only');
                Route::post('/post', 'Sambal@post')->middleware('ajax.only');
                Route::put('/put', 'Sambal@put')->middleware('ajax.only');
              });
              Route::prefix('/tepung')->group(function () {
                Route::get('', 'Tepung@index')->middleware('role:admin');
                Route::get('/get', 'Tepung@get')->middleware('ajax.only');
                Route::get('/search', 'Tepung@search')->middleware('ajax.only');
                Route::post('/post', 'Tepung@post')->middleware('ajax.only');
                Route::put('/put', 'Tepung@put')->middleware('ajax.only');
              });
              Route::prefix('/minyak')->group(function () {
                Route::get('', 'Minyak@index')->middleware('role:admin');
                Route::get('/get', 'Minyak@get')->middleware('ajax.only');
                Route::get('/search', 'Minyak@search')->middleware('ajax.only');
                Route::post('/post', 'Minyak@post')->middleware('ajax.only');
                Route::put('/put', 'Minyak@put')->middleware('ajax.only');
              });
              Route::prefix('/margarin')->group(function () {
                Route::get('', 'Margarin@index')->middleware('role:admin');
                Route::get('/get', 'Margarin@get')->middleware('ajax.only');
                Route::get('/search', 'Margarin@search')->middleware('ajax.only');
                Route::post('/post', 'Margarin@post')->middleware('ajax.only');
                Route::put('/put', 'Margarin@put')->middleware('ajax.only');
              });
              Route::prefix('/lpg')->group(function () {
                Route::get('', 'LPG@index')->middleware('role:admin');
                Route::get('/get', 'LPG@get')->middleware('ajax.only');
                Route::get('/search', 'LPG@search')->middleware('ajax.only');
                Route::post('/post', 'LPG@post')->middleware('ajax.only');
                Route::put('/put', 'LPG@put')->middleware('ajax.only');
              });
            });
          });
          Route::prefix('/quality_control')->group(function () {
            Route::get('', 'QualityControl@qualityControl')->middleware('role:admin');
            Route::get('/detail/{qualityControl}', 'QualityControl@parameterQualityControl')->middleware('role:admin');
            Route::get('/detail/{qualityControl}/{parameterQualityControl}', 'QualityControl@opsiParameterQualityControl')->middleware('role:admin');
            Route::namespace('QualityControl')->group(function () {
              Route::prefix('/quality_control')->group(function () {
                Route::get('/get', 'QualityControl@get')->middleware('ajax.only');
                Route::get('/search', 'QualityControl@search')->middleware('ajax.only');
                Route::post('/post', 'QualityControl@post')->middleware('ajax.only');
                Route::put('/put', 'QualityControl@put')->middleware('ajax.only');
              });
              Route::prefix('/parameter_quality_control')->group(function () {
                Route::get('/get', 'ParameterQualityControl@get')->middleware('ajax.only');
                Route::get('/search', 'ParameterQualityControl@search')->middleware('ajax.only');
                Route::post('/post', 'ParameterQualityControl@post')->middleware('ajax.only');
                Route::put('/put', 'ParameterQualityControl@put')->middleware('ajax.only');
              });
              Route::prefix('/opsi_parameter_quality_control')->group(function () {
                Route::get('/get', 'OpsiParameterQualityControl@get')->middleware('ajax.only');
                Route::get('/search', 'OpsiParameterQualityControl@search')->middleware('ajax.only');
                Route::post('/post', 'OpsiParameterQualityControl@post')->middleware('ajax.only');
                Route::put('/put', 'OpsiParameterQualityControl@put')->middleware('ajax.only');
              });
            });
          });
          Route::prefix('/aktivitas_karyawan')->group(function () {
            Route::get('', 'AktivitasKaryawan@index')->middleware('role:admin');
            Route::get('/get', 'AktivitasKaryawan@get')->middleware('ajax.only');
            Route::get('/search', 'AktivitasKaryawan@search')->middleware('ajax.only');
            Route::post('/post', 'AktivitasKaryawan@post')->middleware('ajax.only');
            Route::put('/put', 'AktivitasKaryawan@put')->middleware('ajax.only');
          });
          Route::prefix('/atribut_karyawan')->group(function () {
            Route::get('', 'AtributKaryawan@atributKaryawan')->middleware('role:admin');
            Route::get('/detail/{atributKaryawan}', 'AtributKaryawan@parameterAtributKaryawan')->middleware('role:admin');
            Route::namespace('AtributKaryawan')->group(function () {
              Route::prefix('/atribut_karyawan')->group(function () {
                Route::get('/get', 'AtributKaryawan@get')->middleware('ajax.only');
                Route::get('/search', 'AtributKaryawan@search')->middleware('ajax.only');
                Route::post('/post', 'AtributKaryawan@post')->middleware('ajax.only');
                Route::put('/put', 'AtributKaryawan@put')->middleware('ajax.only');
              });
              Route::prefix('/parameter_atribut_karyawan')->group(function () {
                Route::get('/get', 'ParameterAtributKaryawan@get')->middleware('ajax.only');
                Route::get('/search', 'ParameterAtributKaryawan@search')->middleware('ajax.only');
                Route::post('/post', 'ParameterAtributKaryawan@post')->middleware('ajax.only');
                Route::put('/put', 'ParameterAtributKaryawan@put')->middleware('ajax.only');
              });
            });
          });
          Route::prefix('/kegiatan_kebersihan')->group(function () {
            Route::get('', 'KegiatanKebersihan@index')->middleware('role:admin');
            Route::get('/get', 'KegiatanKebersihan@get')->middleware('ajax.only');
            Route::get('/search', 'KegiatanKebersihan@search')->middleware('ajax.only');
            Route::post('/post', 'KegiatanKebersihan@post')->middleware('ajax.only');
            Route::put('/put', 'KegiatanKebersihan@put')->middleware('ajax.only');
          });
          Route::prefix('/general_cleaning')->group(function () {
            Route::get('', 'GeneralCleaning@areaGeneralCleaning')->middleware('role:admin');
            Route::get('/detail/{areaGeneralCleaning}', 'GeneralCleaning@kegiatanGeneralCleaning')->middleware('role:admin');
            Route::namespace('GeneralCleaning')->group(function () {
              Route::prefix('/area_general_cleaning')->group(function () {
                Route::get('/get', 'AreaGeneralCleaning@get')->middleware('ajax.only');
                Route::get('/search', 'AreaGeneralCleaning@search')->middleware('ajax.only');
                Route::post('/post', 'AreaGeneralCleaning@post')->middleware('ajax.only');
                Route::put('/put', 'AreaGeneralCleaning@put')->middleware('ajax.only');
              });
              Route::prefix('/kegiatan_general_cleaning')->group(function () {
                Route::get('/get', 'KegiatanGeneralCleaning@get')->middleware('ajax.only');
                Route::get('/search', 'KegiatanGeneralCleaning@search')->middleware('ajax.only');
                Route::post('/post', 'KegiatanGeneralCleaning@post')->middleware('ajax.only');
                Route::put('/put', 'KegiatanGeneralCleaning@put')->middleware('ajax.only');
              });
            });
          });
          Route::prefix('/form_foto')->group(function () {
            Route::get('', 'FormFoto@kelompokFoto')->middleware('role:admin');
            Route::get('/detail', 'FormFoto@dendaFoto')->middleware('role:admin');
            Route::namespace('FormFoto')->group(function () {
              Route::prefix('/kelompok_foto')->group(function () {
                Route::get('/get', 'KelompokFoto@get')->middleware('ajax.only');
                Route::get('/search', 'KelompokFoto@search')->middleware('ajax.only');
                Route::post('/post', 'KelompokFoto@post')->middleware('ajax.only');
                Route::put('/put', 'KelompokFoto@put')->middleware('ajax.only');
              });
              Route::prefix('/denda_foto')->group(function () {
                Route::get('/get', 'DendaFoto@get')->middleware('ajax.only');
                Route::get('/search', 'DendaFoto@search');
                Route::post('/post', 'DendaFoto@post')->middleware('ajax.only');
                Route::put('/put', 'DendaFoto@put')->middleware('ajax.only');
              });
            });
          });
        });
      });
      Route::prefix('/form_c')->group(function () {
        Route::namespace('FormC')->group(function () {
          Route::prefix('/produksi')->group(function () {
            Route::namespace('Produksi')->group(function () {
              Route::redirect('', '/operasional/form_c/produksi/goreng');
              Route::prefix('/thawing_ayam')->group(function () {
                Route::get('', 'ThawingAyam@index');
                Route::get('/get', 'ThawingAyam@get')->middleware('ajax.only');
                Route::get('/search', 'ThawingAyam@search')->middleware('ajax.only');
                Route::get('/cabang_harian', 'ThawingAyam@cabangHarian')->middleware('ajax.only');
                Route::post('/post', 'ThawingAyam@post')->middleware('ajax.only');
                Route::put('/put', 'ThawingAyam@put')->middleware('ajax.only');
                Route::delete('/delete', 'ThawingAyam@delete');
              });
              Route::prefix('/goreng')->group(function () {
                Route::get('', 'Goreng@index');
                Route::get('/get', 'Goreng@get')->middleware('ajax.only');
                Route::get('/search', 'Goreng@search')->middleware('ajax.only');
                Route::get('/cabang_harian', 'Goreng@cabangHarian')->middleware('ajax.only');
                Route::post('/post', 'Goreng@post')->middleware('ajax.only');
                Route::put('/put', 'Goreng@put')->middleware('ajax.only');
                Route::delete('/delete', 'Goreng@delete');
              });
              Route::prefix('/masak_nasi')->group(function () {
                Route::get('', 'MasakNasi@index');
                Route::get('/get', 'MasakNasi@get')->middleware('ajax.only');
                Route::get('/search', 'MasakNasi@search')->middleware('ajax.only');
                Route::get('/cabang_harian', 'MasakNasi@cabangHarian')->middleware('ajax.only');
                Route::post('/post', 'MasakNasi@post')->middleware('ajax.only');
                Route::put('/put', 'MasakNasi@put')->middleware('ajax.only');
                Route::delete('/delete', 'MasakNasi@delete');
              });
              Route::prefix('/sambal')->group(function () {
                Route::get('', 'Sambal@index');
                Route::get('/get', 'Sambal@get')->middleware('ajax.only');
                Route::get('/search', 'Sambal@search')->middleware('ajax.only');
                Route::get('/cabang_harian', 'Sambal@cabangHarian')->middleware('ajax.only');
                Route::post('/post', 'Sambal@post')->middleware('ajax.only');
                Route::put('/put', 'Sambal@put')->middleware('ajax.only');
                Route::delete('/delete', 'Sambal@delete');
              });
              Route::prefix('/tepung')->group(function () {
                Route::get('', 'Tepung@index');
                Route::get('/get', 'Tepung@get')->middleware('ajax.only');
                Route::get('/search', 'Tepung@search')->middleware('ajax.only');
                Route::get('/cabang_harian', 'Tepung@cabangHarian')->middleware('ajax.only');
                Route::post('/post', 'Tepung@post')->middleware('ajax.only');
                Route::put('/put', 'Tepung@put')->middleware('ajax.only');
                Route::delete('/delete', 'Tepung@delete');
              });
              Route::prefix('/minyak')->group(function () {
                Route::get('', 'Minyak@index');
                Route::get('/get', 'Minyak@get')->middleware('ajax.only');
                Route::get('/search', 'Minyak@search')->middleware('ajax.only');
                Route::get('/cabang_harian', 'Minyak@cabangHarian')->middleware('ajax.only');
                Route::post('/post', 'Minyak@post')->middleware('ajax.only');
                Route::put('/put', 'Minyak@put')->middleware('ajax.only');
                Route::delete('/delete', 'Minyak@delete');
              });
              Route::prefix('/margarin')->group(function () {
                Route::get('', 'Margarin@index');
                Route::get('/get', 'Margarin@get')->middleware('ajax.only');
                Route::get('/search', 'Margarin@search')->middleware('ajax.only');
                Route::get('/cabang_harian', 'Margarin@cabangHarian')->middleware('ajax.only');
                Route::post('/post', 'Margarin@post')->middleware('ajax.only');
                Route::put('/put', 'Margarin@put')->middleware('ajax.only');
                Route::delete('/delete', 'Margarin@delete');
              });
              Route::prefix('/lpg')->group(function () {
                Route::get('', 'LPG@index');
                Route::get('/get', 'LPG@get')->middleware('ajax.only');
                Route::get('/search', 'LPG@search')->middleware('ajax.only');
                Route::get('/cabang_harian', 'LPG@cabangHarian')->middleware('ajax.only');
                Route::post('/post', 'LPG@post')->middleware('ajax.only');
                Route::put('/put', 'LPG@put')->middleware('ajax.only');
                Route::delete('/delete', 'LPG@delete');
              });
            });
          });
          Route::prefix('/quality_control')->group(function () {
            Route::get('', 'QualityControl@index');
            Route::get('/detail/{formQualityControl}', 'QualityControl@detail');
            Route::get('/get', 'QualityControl@get')->middleware('ajax.only');
            Route::get('/search', 'QualityControl@search')->middleware('ajax.only');
            Route::get('/cabang_harian', 'QualityControl@cabangHarian')->middleware('ajax.only');
            Route::post('/post', 'QualityControl@post')->middleware('ajax.only');
            Route::put('/put', 'QualityControl@put')->middleware('ajax.only');
            Route::delete('/delete', 'QualityControl@delete');
          });
          Route::prefix('/atribut_karyawan')->group(function () {
            Route::get('', 'AtributKaryawan@index');
            Route::get('/detail/{formAtributKaryawan}', 'AtributKaryawan@detail');
            Route::get('/get', 'AtributKaryawan@get')->middleware('ajax.only');
            Route::get('/search', 'AtributKaryawan@search')->middleware('ajax.only');
            Route::get('/cabang_harian', 'AtributKaryawan@cabangHarian')->middleware('ajax.only');
            Route::post('/post', 'AtributKaryawan@post')->middleware('ajax.only');
            Route::put('/put', 'AtributKaryawan@put')->middleware('ajax.only');
            Route::delete('/delete', 'AtributKaryawan@delete');
          });
          Route::prefix('/kebersihan')->group(function () {
            Route::get('', 'Kebersihan@index');
            Route::get('/detail/{formKebersihan}', 'Kebersihan@detail');
            Route::get('/get', 'Kebersihan@get')->middleware('ajax.only');
            Route::get('/search', 'Kebersihan@search')->middleware('ajax.only');
            Route::get('/cabang_harian', 'Kebersihan@cabangHarian')->middleware('ajax.only');
            Route::post('/post', 'Kebersihan@post')->middleware('ajax.only');
            Route::put('/put', 'Kebersihan@put')->middleware('ajax.only');
            Route::delete('/delete', 'Kebersihan@delete');
          });
          Route::prefix('/general_cleaning')->group(function () {
            Route::get('', 'GeneralCleaning@index');
            Route::get('/detail/{formGeneralCleaning}', 'GeneralCleaning@detail');
            Route::get('/get', 'GeneralCleaning@get')->middleware('ajax.only');
            Route::get('/search', 'GeneralCleaning@search')->middleware('ajax.only');
            Route::get('/cabang_harian', 'GeneralCleaning@cabangHarian')->middleware('ajax.only');
            Route::post('/post', 'GeneralCleaning@post')->middleware('ajax.only');
            Route::put('/put', 'GeneralCleaning@put')->middleware('ajax.only');
            Route::delete('/delete', 'GeneralCleaning@delete');
          });
        });
      });
      Route::prefix('/form_foto')->group(function () {
        Route::get('', 'FormFoto@index');
        Route::get('/get', 'FormFoto@get')->middleware('ajax.only');
        Route::get('/search', 'FormFoto@search')->middleware('ajax.only');
        Route::get('/cabang_harian', 'FormFoto@cabangHarian');
        Route::post('/post', 'FormFoto@post')->middleware('ajax.only');
        Route::put('/put', 'FormFoto@put')->middleware('ajax.only');
        Route::delete('/delete', 'FormFoto@delete')->middleware('ajax.only');
      });
      Route::prefix('/form_denda_foto')->group(function () {
        Route::get('', 'FormDendaFoto@index');
        Route::get('/get', 'FormDendaFoto@get');
        Route::get('/search', 'FormDendaFoto@search');
        Route::get('/cabang_harian', 'FormDendaFoto@cabangHarian');
        Route::post('/post', 'FormDendaFoto@post');
        Route::put('/put', 'FormDendaFoto@put');
        Route::delete('/delete', 'FormDendaFoto@delete');
      });
    });
  });   
});