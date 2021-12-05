<?php
use \ResponseFormatter\ResponseFormatterFactory as ResponseFormatterFactory;

class Response {
  public $body;
  private $http_status_code;

  public function setHttpStatusCode($http_status_code) {
    $this->http_status_code = $http_status_code;
  }


  public function getHttpStatusCode() {
    return $this->http_status_code;
  }


  public function setBody(array $body) {
    $this->body = $body;
  }


  public function getBody() {
    return $this->body;
  }





}
