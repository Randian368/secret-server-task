<?php
namespace Response;

class ErrorResponse extends \Response {

  public function __construct($http_status_code, $message) {
    $this->setHttpStatusCode($http_status_code);
    
    $this->setBody(['error' => [
      'message' => $message
    ]]);
  }
}
