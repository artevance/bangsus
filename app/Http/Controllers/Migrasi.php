<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Http\Models\Gambar;
use App\Http\Models\FormThawingAyam;
use App\Http\Models\FormGoreng;
use App\Http\Models\FormMasakNasi;
use App\Http\Models\FormSambal;
use App\Http\Models\FormTepung;
use App\Http\Models\FormMinyak;
use App\Http\Models\FormMargarin;
use App\Http\Models\FormLPG;

class Migrasi extends Controller
{
  public function index(Request $request)
  {
    $outerDBName = 'bucillop_db_bangsus_hrd';
    echo 'Begin transaction';
    try {
      echo '<br><br>';
      echo 'Form Thawing Ayam';
      echo '<br>';
      echo '=========';
      $container = DB::table($outerDBName . '.ops_form_thawing_ayam')->get();
      $dataCount = count($container);
      $i = 1;
      foreach ($container as $data) {
        if ( ! is_null($data->image_dir) && $data->image_dir != '') {
          $filename = explode('public/', $data->image_dir)[1];

          $file = File::get($filename);

          $gambar = new Gambar;
          $gambar->konten = $file;
          $gambar->save();
          echo 'Image: ' . $filename . ' migrated'; echo '<br>';

          $formThawingAyam = FormThawingAyam::find($data->form_thawing_ayam_id);
          $formThawingAyam->gambar_id = $gambar->id;
          $formThawingAyam->save();
          echo 'Data migrated (' . $i . '/' . $dataCount . ') ... ' . $i / $dataCount * 100 . ' %'; echo '<br>';
        } else {
          echo 'Image not found. Failed to migrate.';
          echo 'Data not migrated (' . $i . '/' . $dataCount . ') ... ' . $i / $dataCount * 100 . '%'; echo '<br>';
        }
        $i++;
      }
      echo '<br><br>';
      echo 'Form Goreng';
      echo '<br>';
      echo '=========';
      $container = DB::table($outerDBName . '.ops_form_goreng')->get();
      $dataCount = count($container);
      $i = 1;
      foreach ($container as $data) {
        if ( ! is_null($data->image_dir) && $data->image_dir != '') {
          $filename = explode('public/', $data->image_dir)[1];

          $file = File::get($filename);

          $gambar = new Gambar;
          $gambar->konten = $file;
          $gambar->save();
          echo 'Image: ' . $filename . ' migrated'; echo '<br>';

          $formGoreng = FormGoreng::find($data->form_goreng_id);
          $formGoreng->gambar_id = $gambar->id;
          $formGoreng->save();
          echo 'Data migrated (' . $i . '/' . $dataCount . ') ... ' . $i / $dataCount * 100 . ' %'; echo '<br>';
        } else {
          echo 'Image not found. Failed to migrate.';
          echo 'Data not migrated (' . $i . '/' . $dataCount . ') ... ' . $i / $dataCount * 100 . '%'; echo '<br>';
        }
        $i++;
      }
      echo '<br><br>';
      echo 'Form Masak Nasi';
      echo '<br>';
      echo '=========';
      $container = DB::table($outerDBName . '.ops_form_masak_nasi')->get();
      $dataCount = count($container);
      $i = 1;
      foreach ($container as $data) {
        if ( ! is_null($data->image_dir) && $data->image_dir != '') {
          $filename = explode('public/', $data->image_dir)[1];

          $file = File::get($filename);

          $gambar = new Gambar;
          $gambar->konten = $file;
          $gambar->save();
          echo 'Image: ' . $filename . ' migrated'; echo '<br>';

          $formMasakNasi = FormMasakNasi::find($data->form_masak_nasi_id);
          $formMasakNasi->gambar_id = $gambar->id;
          $formMasakNasi->save();
          echo 'Data migrated (' . $i . '/' . $dataCount . ') ... ' . $i / $dataCount * 100 . ' %'; echo '<br>';
        } else {
          echo 'Image not found. Failed to migrate.';
          echo 'Data not migrated (' . $i . '/' . $dataCount . ') ... ' . $i / $dataCount * 100 . '%'; echo '<br>';
        }
        $i++;
      }
      echo '<br><br>';
      echo 'Form Sambal';
      echo '<br>';
      echo '=========';
      $container = DB::table($outerDBName . '.ops_form_sambal')->get();
      $dataCount = count($container);
      $i = 1;
      foreach ($container as $data) {
        if ( ! is_null($data->image_dir) && $data->image_dir != '') {
          $filename = explode('public/', $data->image_dir)[1];

          $file = File::get($filename);

          $gambar = new Gambar;
          $gambar->konten = $file;
          $gambar->save();
          echo 'Image: ' . $filename . ' migrated'; echo '<br>';

          $formSambal = FormSambal::find($data->form_sambal_id);
          $formSambal->gambar_id = $gambar->id;
          $formSambal->save();
          echo 'Data migrated (' . $i . '/' . $dataCount . ') ... ' . $i / $dataCount * 100 . ' %'; echo '<br>';
        } else {
          echo 'Image not found. Failed to migrate.';
          echo 'Data not migrated (' . $i . '/' . $dataCount . ') ... ' . $i / $dataCount * 100 . '%'; echo '<br>';
        }
        $i++;
      }
      echo '<br><br>';
      echo 'Form Tepung';
      echo '<br>';
      echo '=========';
      $container = DB::table($outerDBName . '.ops_form_tepung')->get();
      $dataCount = count($container);
      $i = 1;
      foreach ($container as $data) {
        if ( ! is_null($data->image_dir) && $data->image_dir != '') {
          $filename = explode('public/', $data->image_dir)[1];

          $file = File::get($filename);

          $gambar = new Gambar;
          $gambar->konten = $file;
          $gambar->save();
          echo 'Image: ' . $filename . ' migrated'; echo '<br>';

          $formTepung = FormTepung::find($data->form_tepung_id);
          $formTepung->gambar_id = $gambar->id;
          $formTepung->save();
          echo 'Data migrated (' . $i . '/' . $dataCount . ') ... ' . $i / $dataCount * 100 . ' %'; echo '<br>';
        } else {
          echo 'Image not found. Failed to migrate.';
          echo 'Data not migrated (' . $i . '/' . $dataCount . ') ... ' . $i / $dataCount * 100 . '%'; echo '<br>';
        }
        $i++;
      }
      echo '<br><br>';
      echo 'Form Minyak';
      echo '<br>';
      echo '=========';
      $container = DB::table($outerDBName . '.ops_form_minyak')->get();
      $dataCount = count($container);
      $i = 1;
      foreach ($container as $data) {
        if ( ! is_null($data->image_dir) && $data->image_dir != '') {
          $filename = explode('public/', $data->image_dir)[1];

          $file = File::get($filename);

          $gambar = new Gambar;
          $gambar->konten = $file;
          $gambar->save();
          echo 'Image: ' . $filename . ' migrated'; echo '<br>';

          $formMinyak = FormMinyak::find($data->form_minyak_id);
          $formMinyak->gambar_id = $gambar->id;
          $formMinyak->save();
          echo 'Data migrated (' . $i . '/' . $dataCount . ') ... ' . $i / $dataCount * 100 . ' %'; echo '<br>';
        } else {
          echo 'Image not found. Failed to migrate.';
          echo 'Data not migrated (' . $i . '/' . $dataCount . ') ... ' . $i / $dataCount * 100 . '%'; echo '<br>';
        }
        $i++;
      }
      echo '<br><br>';
      echo 'Form Margarin';
      echo '<br>';
      echo '=========';
      $container = DB::table($outerDBName . '.ops_form_margarin')->get();
      $dataCount = count($container);
      $i = 1;
      foreach ($container as $data) {
        if ( ! is_null($data->image_dir) && $data->image_dir != '') {
          $filename = explode('public/', $data->image_dir)[1];

          $file = File::get($filename);

          $gambar = new Gambar;
          $gambar->konten = $file;
          $gambar->save();
          echo 'Image: ' . $filename . ' migrated'; echo '<br>';

          $formMargarin = FormMargarin::find($data->form_margarin_id);
          $formMargarin->gambar_id = $gambar->id;
          $formMargarin->save();
          echo 'Data migrated (' . $i . '/' . $dataCount . ') ... ' . $i / $dataCount * 100 . ' %'; echo '<br>';
        } else {
          echo 'Image not found. Failed to migrate.';
          echo 'Data not migrated (' . $i . '/' . $dataCount . ') ... ' . $i / $dataCount * 100 . '%'; echo '<br>';
        }
        $i++;
      }
      echo '<br><br>';
      echo 'Form LPG';
      echo '<br>';
      echo '=========';
      $container = DB::table($outerDBName . '.ops_form_lpg')->get();
      $dataCount = count($container);
      $i = 1;
      foreach ($container as $data) {
        if ( ! is_null($data->image_dir) && $data->image_dir != '') {
          $filename = explode('public/', $data->image_dir)[1];

          $file = File::get($filename);

          $gambar = new Gambar;
          $gambar->konten = $file;
          $gambar->save();
          echo 'Image: ' . $filename . ' migrated'; echo '<br>';

          $formLPG = FormLPG::find($data->form_lpg_id);
          $formLPG->gambar_id = $gambar->id;
          $formLPG->save();
          echo 'Data migrated (' . $i . '/' . $dataCount . ') ... ' . $i / $dataCount * 100 . ' %'; echo '<br>';
        } else {
          echo 'Image not found. Failed to migrate.';
          echo 'Data not migrated (' . $i . '/' . $dataCount . ') ... ' . $i / $dataCount * 100 . '%'; echo '<br>';
        }
        $i++;
      }
    } catch (\Exception $e) {
      DB::table('form_thawing_ayam')->update(['gambar_id' => null]);
      DB::table('form_goreng')->update(['gambar_id' => null]);
      DB::table('form_masak_nasi')->update(['gambar_id' => null]);
      DB::table('form_tepung')->update(['gambar_id' => null]);
      DB::table('form_sambal')->update(['gambar_id' => null]);
      DB::table('form_minyak')->update(['gambar_id' => null]);
      DB::table('form_margarin')->update(['gambar_id' => null]);
      DB::table('form_lpg')->update(['gambar_id' => null]);
      DB::table('gambar')->delete();
      DB::statement('ALTER TABLE gambar AUTO_INCREMENT = 1');
      echo '<br><br><br>';
      echo 'There was an exception:';
      echo '<br>';
      echo $e->getMessage();
    }

    echo '<br><br>';
    echo 'End transaction';
  }
}