<?php

namespace App\Templates;

class Ajax
{
  public static function v1_0($container = [])
  {
    $data = $container['data'] ?? [];
    $errors = $container['errors'] ?? [];
    $meta = $container['meta'] ?? [];

    return [
      'app_name' => 'Bangsus App',
      'slug' => 'bangsus_app',
      'api_version' => '1.0',
      'container' => $data,
      'errors' => $errors,
      'meta' => $meta
    ];
  }
}