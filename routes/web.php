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

Route::middleware('check.not.logged.in')->group(function () {  
  Route::redirect('/', '/home');
  Route::get('/login', 'Auth\Login@index')->name('login');
  Route::post('/authenticate', 'Auth\Authenticate@index');
});

Route::middleware('auth')->group(function () {
  Route::get('/logout', 'Auth\Logout@index');
  Route::get('/dashboard', 'Dashboard@index');
  Route::get('/gambar/{gambar}', 'Gambar@index');

  // Only Admin can access
  Route::prefix('/hrd')->group(function () {
    Route::namespace('HRD')->group(function () {

      Route::prefix('/master')->group(function () {
        Route::namespace('Master')->group(function () {
          Route::prefix('/tipe_kontak')->group(function () {
            Route::get('', 'TipeKontak@index')->middleware('role:admin');
            Route::get('/get', 'TipeKontak@get');
            Route::get('/search', 'TipeKontak@search');
            Route::post('/post', 'TipeKontak@post');
            Route::put('/put', 'TipeKontak@put');
          });
          Route::prefix('/tipe_alamat')->group(function () {
            Route::get('', 'TipeAlamat@index')->middleware('role:admin');
            Route::get('/get', 'TipeAlamat@get');
            Route::get('/search', 'TipeAlamat@search');
            Route::post('/post', 'TipeAlamat@post');
            Route::put('/put', 'TipeAlamat@put');
          });
          Route::prefix('/tipe_foto_karyawan')->group(function () {
            Route::get('', 'TipeFotoKaryawan@index')->middleware('role:admin');
            Route::get('/get', 'TipeFotoKaryawan@get');
            Route::get('/search', 'TipeFotoKaryawan@search');
            Route::post('/post', 'TipeFotoKaryawan@post');
            Route::put('/put', 'TipeFotoKaryawan@put');
          });
          Route::prefix('/jabatan')->group(function () {
            Route::get('', 'Jabatan@index')->middleware('role:admin');
            Route::get('/get', 'Jabatan@get');
            Route::get('/search', 'Jabatan@search');
            Route::post('/post', 'Jabatan@post');
            Route::put('/put', 'Jabatan@put');
          });
          Route::prefix('/divisi')->group(function () {
            Route::get('', 'Divisi@index')->middleware('role:admin');
            Route::get('/get', 'Divisi@get');
            Route::get('/search', 'Divisi@search');
            Route::post('/post', 'Divisi@post');
            Route::put('/put', 'Divisi@put');
          });
          Route::prefix('/tipe_cabang')->group(function () {
            Route::get('', 'TipeCabang@index')->middleware('role:admin');
            Route::get('/get', 'TipeCabang@get');
            Route::get('/search', 'TipeCabang@search');
            Route::post('/post', 'TipeCabang@post');
            Route::put('/put', 'TipeCabang@put');
          });
          Route::prefix('/cabang')->group(function () {
            Route::get('', 'Cabang@index')->middleware('role:admin');
            Route::get('/get', 'Cabang@get');
            Route::get('/search', 'Cabang@search');
            Route::post('/post', 'Cabang@post');
            Route::put('/put', 'Cabang@put');
          });
          Route::prefix('/jenis_kelamin')->group(function () {
            Route::get('', 'JenisKelamin@index')->middleware('role:admin');
            Route::get('/get', 'JenisKelamin@get');
            Route::get('/search', 'JenisKelamin@search');
          });
          Route::prefix('/golongan_darah')->group(function () {
            Route::get('', 'GolonganDarah@index')->middleware('role:admin');
            Route::get('/get', 'GolonganDarah@get');
            Route::get('/search', 'GolonganDarah@search');
          });
          Route::prefix('/tipe_absensi')->group(function () {
            Route::get('', 'TipeAbsensi@index')->middleware('role:admin');
            Route::get('/get', 'TipeAbsensi@get');
            Route::get('/search', 'TipeAbsensi@search');
            Route::post('/post', 'TipeAbsensi@post');
            Route::put('/put', 'TipeAbsensi@put');
          });
        });
      });

      Route::prefix('/karyawan')->group(function () {
        Route::get('', 'Karyawan@index')->middleware('role:admin');
        Route::get('/get', 'Karyawan@get');
        Route::get('/search', 'Karyawan@search');
        Route::post('/post', 'Karyawan@post');
        Route::put('/put', 'Karyawan@put');
      });
      Route::prefix('/tugas_karyawan')->group(function () {
        Route::get('/karyawan/{karyawan}', 'TugasKaryawan@karyawan');
        Route::get('/get', 'TugasKaryawan@get');
        Route::get('/search', 'TugasKaryawan@search');
        Route::post('/post', 'TugasKaryawan@post');
        Route::put('/put', 'TugasKaryawan@put');
      });

      Route::prefix('/absensi')->group(function () {

        Route::get('/get', 'Absensi@get');
        Route::get('/search', 'Absensi@search');
        Route::post('/post', 'Absensi@post');
        Route::put('/put', 'Absensi@put');
        Route::namespace('Absensi')->group(function () {

          Route::prefix('/manual')->group(function () {
            Route::get('', 'Manual@index');
            Route::get('/cabang_tipe_harian', 'Manual@cabangTipeHarian');
          });
          Route::prefix('/impor_jadwal')->group(function () {
            Route::get('', 'ImporJadwal@index');
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
            Route::get('/get', 'Satuan@get');
            Route::get('/search', 'Satuan@search');
            Route::post('/post', 'Satuan@post');
            Route::put('/put', 'Satuan@put');
          });
          Route::prefix('/supplier')->group(function () {
            Route::get('', 'Supplier@index')->middleware('role:admin');
            Route::get('/get', 'Supplier@get');
            Route::get('/search', 'Supplier@search');
            Route::post('/post', 'Supplier@post');
            Route::put('/put', 'Supplier@put');
          });
          Route::prefix('/item_goreng')->group(function () {
            Route::get('', 'ItemGoreng@index')->middleware('role:admin');
            Route::get('/get', 'ItemGoreng@get');
            Route::get('/search', 'ItemGoreng@search');
            Route::post('/post', 'ItemGoreng@post');
            Route::put('/put', 'ItemGoreng@put');
          });
          Route::prefix('/tipe_proses_produksi')->group(function () {
            Route::namespace('TipeProsesProduksi')->group(function () {
              Route::redirect('', '/operasional/master/tipe_proses_produksi/sambal');
              Route::prefix('/sambal')->group(function () {
                Route::get('', 'Sambal@index')->middleware('role:admin');
                Route::get('/get', 'Sambal@get');
                Route::get('/search', 'Sambal@search');
                Route::post('/post', 'Sambal@post');
                Route::put('/put', 'Sambal@put');
              });
              Route::prefix('/tepung')->group(function () {
                Route::get('', 'Tepung@index')->middleware('role:admin');
                Route::get('/get', 'Tepung@get');
                Route::get('/search', 'Tepung@search');
                Route::post('/post', 'Tepung@post');
                Route::put('/put', 'Tepung@put');
              });
              Route::prefix('/minyak')->group(function () {
                Route::get('', 'Minyak@index')->middleware('role:admin');
                Route::get('/get', 'Minyak@get');
                Route::get('/search', 'Minyak@search');
                Route::post('/post', 'Minyak@post');
                Route::put('/put', 'Minyak@put');
              });
              Route::prefix('/margarin')->group(function () {
                Route::get('', 'Margarin@index')->middleware('role:admin');
                Route::get('/get', 'Margarin@get');
                Route::get('/search', 'Margarin@search');
                Route::post('/post', 'Margarin@post');
                Route::put('/put', 'Margarin@put');
              });
              Route::prefix('/lpg')->group(function () {
                Route::get('', 'LPG@index')->middleware('role:admin');
                Route::get('/get', 'LPG@get');
                Route::get('/search', 'LPG@search');
                Route::post('/post', 'LPG@post');
                Route::put('/put', 'LPG@put');
              });
            });
          });
          Route::prefix('/quality_control')->group(function () {
            Route::get('', 'QualityControl@qualityControl')->middleware('role:admin');
            Route::get('/detail/{qualityControl}', 'QualityControl@parameterQualityControl')->middleware('role:admin');
            Route::get('/detail/{qualityControl}/{parameterQualityControl}', 'QualityControl@opsiParameterQualityControl')->middleware('role:admin');
            Route::namespace('QualityControl')->group(function () {
              Route::prefix('/quality_control')->group(function () {
                Route::get('/get', 'QualityControl@get');
                Route::get('/search', 'QualityControl@search');
                Route::post('/post', 'QualityControl@post');
                Route::put('/put', 'QualityControl@put');
              });
              Route::prefix('/parameter_quality_control')->group(function () {
                Route::get('/get', 'ParameterQualityControl@get');
                Route::get('/search', 'ParameterQualityControl@search');
                Route::post('/post', 'ParameterQualityControl@post');
                Route::put('/put', 'ParameterQualityControl@put');
              });
              Route::prefix('/opsi_parameter_quality_control')->group(function () {
                Route::get('/get', 'OpsiParameterQualityControl@get');
                Route::get('/search', 'OpsiParameterQualityControl@search');
                Route::post('/post', 'OpsiParameterQualityControl@post');
                Route::put('/put', 'OpsiParameterQualityControl@put');
              });
            });
          });
          Route::prefix('/aktivitas_karyawan')->group(function () {
            Route::get('', 'AktivitasKaryawan@index')->middleware('role:admin');
            Route::get('/get', 'AktivitasKaryawan@get');
            Route::get('/search', 'AktivitasKaryawan@search');
            Route::post('/post', 'AktivitasKaryawan@post');
            Route::put('/put', 'AktivitasKaryawan@put');
          });
          Route::prefix('/atribut_karyawan')->group(function () {
            Route::get('', 'AtributKaryawan@qualityControl')->middleware('role:admin');
            Route::get('/detail/{atributKaryawan}', 'AtributKaryawan@parameterAtributKaryawan')->middleware('role:admin');
            Route::namespace('AtributKaryawan')->group(function () {
              Route::prefix('/atribut_karyawan')->group(function () {
                Route::get('/get', 'AtributKaryawan@get');
                Route::get('/search', 'AtributKaryawan@search');
                Route::post('/post', 'AtributKaryawan@post');
                Route::put('/put', 'AtributKaryawan@put');
              });
              Route::prefix('/parameter_atribut_karyawan')->group(function () {
                Route::get('/get', 'ParameterAtributKaryawan@get');
                Route::get('/search', 'ParameterAtributKaryawan@search');
                Route::post('/post', 'ParameterAtributKaryawan@post');
                Route::put('/put', 'ParameterAtributKaryawan@put');
              });
            });
          });
          Route::prefix('/kegiatan_kebersihan')->group(function () {
            Route::get('', 'KegiatanKebersihan@index')->middleware('role:admin');
            Route::get('/get', 'KegiatanKebersihan@get');
            Route::get('/search', 'KegiatanKebersihan@search');
            Route::post('/post', 'KegiatanKebersihan@post');
            Route::put('/put', 'KegiatanKebersihan@put');
          });
          Route::prefix('/general_cleaning')->group(function () {
            Route::get('', 'GeneralCleaning@areaGeneralCleaning')->middleware('role:admin');
            Route::get('/detail/{areaGeneralCleaning}', 'GeneralCleaning@kegiatanGeneralCleaning')->middleware('role:admin');
            Route::namespace('GeneralCleaning')->group(function () {
              Route::prefix('/area_general_cleaning')->group(function () {
                Route::get('/get', 'Area@get');
                Route::get('/search', 'Area@search');
                Route::post('/post', 'Area@post');
                Route::put('/put', 'Area@put');
              });
              Route::prefix('/kegiatan')->group(function () {
                Route::get('/get', 'Kegiatan@get');
                Route::get('/search', 'Kegiatan@search');
                Route::post('/post', 'Kegiatan@post');
                Route::put('/put', 'Kegiatan@put');
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
                Route::get('/get', 'ThawingAyam@get');
                Route::get('/search', 'ThawingAyam@search');
                Route::get('/cabang_harian', 'ThawingAyam@cabangHarian');
                Route::post('/post', 'ThawingAyam@post');
                Route::put('/put', 'ThawingAyam@put');
                Route::delete('/delete', 'ThawingAyam@delete');
              });
              Route::prefix('/goreng')->group(function () {
                Route::get('', 'Goreng@index');
                Route::get('/get', 'Goreng@get');
                Route::get('/search', 'Goreng@search');
                Route::get('/cabang_harian', 'Goreng@cabangHarian');
                Route::post('/post', 'Goreng@post');
                Route::put('/put', 'Goreng@put');
                Route::delete('/delete', 'Goreng@delete');
              });
              Route::prefix('/masak_nasi')->group(function () {
                Route::get('', 'MasakNasi@index');
                Route::get('/get', 'MasakNasi@get');
                Route::get('/search', 'MasakNasi@search');
                Route::get('/cabang_harian', 'MasakNasi@cabangHarian');
                Route::post('/post', 'MasakNasi@post');
                Route::put('/put', 'MasakNasi@put');
                Route::delete('/delete', 'MasakNasi@delete');
              });
              Route::prefix('/sambal')->group(function () {
                Route::get('', 'Sambal@index');
                Route::get('/get', 'Sambal@get');
                Route::get('/search', 'Sambal@search');
                Route::get('/cabang_harian', 'Sambal@cabangHarian');
                Route::post('/post', 'Sambal@post');
                Route::put('/put', 'Sambal@put');
                Route::delete('/delete', 'Sambal@delete');
              });
              Route::prefix('/tepung')->group(function () {
                Route::get('', 'Tepung@index');
                Route::get('/get', 'Tepung@get');
                Route::get('/search', 'Tepung@search');
                Route::get('/cabang_harian', 'Tepung@cabangHarian');
                Route::post('/post', 'Tepung@post');
                Route::put('/put', 'Tepung@put');
                Route::delete('/delete', 'Tepung@delete');
              });
              Route::prefix('/minyak')->group(function () {
                Route::get('', 'Minyak@index');
                Route::get('/get', 'Minyak@get');
                Route::get('/search', 'Minyak@search');
                Route::get('/cabang_harian', 'Minyak@cabangHarian');
                Route::post('/post', 'Minyak@post');
                Route::put('/put', 'Minyak@put');
                Route::delete('/delete', 'Minyak@delete');
              });
              Route::prefix('/margarin')->group(function () {
                Route::get('', 'Margarin@index');
                Route::get('/get', 'Margarin@get');
                Route::get('/search', 'Margarin@search');
                Route::get('/cabang_harian', 'Margarin@cabangHarian');
                Route::post('/post', 'Margarin@post');
                Route::put('/put', 'Margarin@put');
                Route::delete('/delete', 'Margarin@delete');
              });
              Route::prefix('/lpg')->group(function () {
                Route::get('', 'LPG@index');
                Route::get('/get', 'LPG@get');
                Route::get('/search', 'LPG@search');
                Route::get('/cabang_harian', 'LPG@cabangHarian');
                Route::post('/post', 'LPG@post');
                Route::put('/put', 'LPG@put');
                Route::delete('/delete', 'LPG@delete');
              });
            });
          });
          Route::prefix('/quality_control')->group(function () {
            Route::get('', 'QualityControl@index');
            Route::get('/get', 'QualityControl@get');
            Route::get('/search', 'QualityControl@search');
            Route::get('/cabang_harian', 'QualityControl@cabangHarian');
            Route::post('/post', 'QualityControl@post');
            Route::put('/put', 'QualityControl@put');
            Route::delete('/delete', 'QualityControl@delete');
          });
        });
      });
    });
  });


    
});