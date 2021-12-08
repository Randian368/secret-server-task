<?php
namespace Response;

/**
 * @purpose
 * A class for structured API error responses.
 */
class ErrorResponse extends \Response {

  public function __construct($http_status_code, $message) {
    $this->setHttpStatusCode($http_status_code);

    $this->setBody(['Error' => [
      'Message' => $message
    ]]);
  }
}
