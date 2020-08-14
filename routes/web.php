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
          });
        });

        Route::prefix('/karyawan')->group(function () {
          Route::namespace('Karyawan')->group(function () {
            Route::prefix('/registrasi')->group(function () {
              Route::get('', 'Registrasi@index');
              Route::post('/post', 'TipeKontak@post');
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