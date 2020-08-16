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

Route::get('/deb', function () {
  dd(\Illuminate\Support\Facades\Auth::user()->role);
});

Route::middleware('check.not.logged.in')->group(function () {  
  Route::redirect('/', '/home');
  Route::get('/login', 'Auth\Login@index');
  Route::post('/authenticate', 'Auth\Authenticate@index');
});

Route::middleware('auth')->group(function () {
  Route::get('/logout', 'Auth\Logout@index');
  Route::get('/dashboard', 'Dashboard@index');

  // Only Admin can access
  Route::middleware('role:admin')->group(function () {

    Route::prefix('/hrd')->group(function () {
      Route::namespace('HRD')->group(function () {

        Route::prefix('/master')->group(function () {
          Route::namespace('Master')->group(function () {
            Route::prefix('/tipe_kontak')->group(function () {
              Route::get('', 'TipeKontak@index');
              Route::get('/get', 'TipeKontak@get');
              Route::get('/search', 'TipeKontak@search');
              Route::post('/post', 'TipeKontak@post');
              Route::put('/put', 'TipeKontak@put');
            });
            Route::prefix('/tipe_alamat')->group(function () {
              Route::get('', 'TipeAlamat@index');
              Route::get('/get', 'TipeAlamat@get');
              Route::get('/search', 'TipeAlamat@search');
              Route::post('/post', 'TipeAlamat@post');
              Route::put('/put', 'TipeAlamat@put');
            });
            Route::prefix('/tipe_foto_karyawan')->group(function () {
              Route::get('', 'TipeFotoKaryawan@index');
              Route::get('/get', 'TipeFotoKaryawan@get');
              Route::get('/search', 'TipeFotoKaryawan@search');
              Route::post('/post', 'TipeFotoKaryawan@post');
              Route::put('/put', 'TipeFotoKaryawan@put');
            });
            Route::prefix('/jabatan')->group(function () {
              Route::get('', 'Jabatan@index');
              Route::get('/get', 'Jabatan@get');
              Route::get('/search', 'Jabatan@search');
              Route::post('/post', 'Jabatan@post');
              Route::put('/put', 'Jabatan@put');
            });
            Route::prefix('/divisi')->group(function () {
              Route::get('', 'Divisi@index');
              Route::get('/get', 'Divisi@get');
              Route::get('/search', 'Divisi@search');
              Route::post('/post', 'Divisi@post');
              Route::put('/put', 'Divisi@put');
            });
            Route::prefix('/tipe_cabang')->group(function () {
              Route::get('', 'TipeCabang@index');
              Route::get('/get', 'TipeCabang@get');
              Route::get('/search', 'TipeCabang@search');
              Route::post('/post', 'TipeCabang@post');
              Route::put('/put', 'TipeCabang@put');
            });
            Route::prefix('/cabang')->group(function () {
              Route::get('', 'Cabang@index');
              Route::get('/get', 'Cabang@get');
              Route::get('/search', 'Cabang@search');
              Route::post('/post', 'Cabang@post');
              Route::put('/put', 'Cabang@put');
            });
            Route::prefix('/jenis_kelamin')->group(function () {
              Route::get('', 'JenisKelamin@index');
              Route::get('/get', 'JenisKelamin@get');
              Route::get('/search', 'JenisKelamin@search');
            });
            Route::prefix('/golongan_darah')->group(function () {
              Route::get('', 'GolonganDarah@index');
              Route::get('/get', 'GolonganDarah@get');
              Route::get('/search', 'GolonganDarah@search');
            });
            Route::prefix('/tipe_absensi')->group(function () {
              Route::get('', 'TipeAbsensi@index');
              Route::get('/get', 'TipeAbsensi@get');
              Route::get('/search', 'TipeAbsensi@search');
              Route::post('/post', 'TipeAbsensi@post');
              Route::put('/put', 'TipeAbsensi@put');
            });
          });
        });

        Route::prefix('/karyawan')->group(function () {
          Route::get('', 'Karyawan@index');
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
              Route::get('', 'Satuan@index');
              Route::get('/get', 'Satuan@get');
              Route::get('/search', 'Satuan@search');
              Route::post('/post', 'Satuan@post');
              Route::put('/put', 'Satuan@put');
            });
            Route::prefix('/supplier')->group(function () {
              Route::get('', 'Supplier@index');
              Route::get('/get', 'Supplier@get');
              Route::get('/search', 'Supplier@search');
              Route::post('/post', 'Supplier@post');
              Route::put('/put', 'Supplier@put');
            });
            Route::prefix('/item_goreng')->group(function () {
              Route::get('', 'ItemGoreng@index');
              Route::get('/get', 'ItemGoreng@get');
              Route::get('/search', 'ItemGoreng@search');
              Route::post('/post', 'ItemGoreng@post');
              Route::put('/put', 'ItemGoreng@put');
            });
            Route::prefix('/tipe_proses_produksi')->group(function () {
              Route::namespace('TipeProsesProduksi')->group(function () {
                Route::redirect('', '/operasional/master/tipe_proses_produksi/sambal');
                Route::prefix('/sambal')->group(function () {
                  Route::get('', 'Sambal@index');
                  Route::get('/get', 'Sambal@get');
                  Route::get('/search', 'Sambal@search');
                  Route::post('/post', 'Sambal@post');
                  Route::put('/put', 'Sambal@put');
                });
                Route::prefix('/tepung')->group(function () {
                  Route::get('', 'Tepung@index');
                  Route::get('/get', 'Tepung@get');
                  Route::get('/search', 'Tepung@search');
                  Route::post('/post', 'Tepung@post');
                  Route::put('/put', 'Tepung@put');
                });
                Route::prefix('/minyak')->group(function () {
                  Route::get('', 'Minyak@index');
                  Route::get('/get', 'Minyak@get');
                  Route::get('/search', 'Minyak@search');
                  Route::post('/post', 'Minyak@post');
                  Route::put('/put', 'Minyak@put');
                });
                Route::prefix('/margarin')->group(function () {
                  Route::get('', 'Margarin@index');
                  Route::get('/get', 'Margarin@get');
                  Route::get('/search', 'Margarin@search');
                  Route::post('/post', 'Margarin@post');
                  Route::put('/put', 'Margarin@put');
                });
                Route::prefix('/lpg')->group(function () {
                  Route::get('', 'LPG@index');
                  Route::get('/get', 'LPG@get');
                  Route::get('/search', 'LPG@search');
                  Route::post('/post', 'LPG@post');
                  Route::put('/put', 'LPG@put');
                });
              });
            });
          });
        });
      });
    });
  });

  // Only Leader can access
  Route::middleware('role:leader')->group(function () {
    
  });
});