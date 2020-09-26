<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller as MainController;
use App\Templates\Ajax;

class Controller extends MainController
{
  protected $response = [];

  protected $data = [];

  protected $errors = [];

  protected $meta = [];

  protected function data($data)
  {
    $this->data = $data;
    return $this;
  }

  protected function errors($errors)
  {
    $this->errors = $errors;
    return $this;
  }

  protected function meta($result)
  {
    $this->meta = [
      'row' => $result->perPage(),
      'count' => $result->count(),
      'total' => $result->total(),
      'first_item' => $result->firstItem(),
      'last_item' => $result->lastItem(),
      'has_pages' => $result->hasPages(),
      'current_page' => $result->currentPage(),
      'last_page' => $result->lastPage()
    ];

    return $this;
  }

  protected function errorsVal($validator)
  {
    $this->errors($validator->errors());
    return $this;
  }

  protected function response($code)
  {
    switch ($this->version) {
      case '1.0':
        $this->response = Ajax::v1_0([
          'data' => $this->data, 
          'errors' => $this->errors,
          'meta' => $this->meta
        ]);
        break;
    }

    return response()->json($this->response, $code);
  }

  const QUERY_ALLOWED_ALL_WILDCARD = ['*', 'all'];

  const PAGINATION_DEFAULT_ROW = 25;
  const PAGINATION_ALLOWED_ALL_WILDCARD = ['*', 'all'];
}