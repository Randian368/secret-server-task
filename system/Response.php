<?php
use \ResponseFormatter\ResponseFormatterFactory as ResponseFormatterFactory;

class Response {
  private $formatter;
  private $response_body;

  public function __construct($response_body) {
    $request_headers = apache_request_headers();
    $this->formatter = ResponseFormatterFactory::createFormatter($request_headers['Accept'] ?: '');
    $this->response_body = $response_body;
  }


  public function get() {
    return $this->formatter->format($this->response_body);
  }
}
