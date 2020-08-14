<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
  protected $title = 'Untitled';

  protected $role = '';

  protected $serializedQuery = [];

  protected $query = [];

  protected function title(string $title) : Controller
  {
    $this->title = $title;
    return $this;
  }

  protected function role(string $role) : Controller
  {
    $this->role = $role;
    return $this;
  }

  protected function query($query) : Controller
  {
    foreach ($query as $key => $value) {
      $this->serializedQuery[] = [
        'name' => $key,
        'value' => $value
      ];
    }
    $this->query = $query;
    return $this;
  }

  protected function passParams(array $data = []) : array
  {
    return array_merge([
      'title' => $this->title,
      'role' => $this->role,
      'query' => $this->query,
      'serializedQuery' => $this->serializedQuery
    ], $data);
  }
}
