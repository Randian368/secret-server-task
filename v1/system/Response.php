<?php
use \ResponseFormatter\ResponseFormatterFactory as ResponseFormatterFactory;

class Response {
  public $body;
  private $http_status_code;
  private $http_headers = [];

  public function setHttpStatusCode($http_status_code) {
    $this->http_status_code = $http_status_code;
  }


  public function getHttpStatusCode() {
    return $this->http_status_code ?: 200;
  }


  public function setBody($body) {
    $this->body = $body;
  }


  public function getBody() {
    return $this->body;
  }


  public function setHttpHeaders(array $http_headers) {
    $this->http_headers = $http_headers;
  }


  public function getHttpHeaders() {
    return $this->http_headers;
  }


  public function setHttpHeader($name, $value) {
    $this->http_headers[$name] = $value;
  }


  public function output() {
    $http_status_code = $this->getHttpStatusCode();
    http_response_code($http_status_code);

    $http_headers = $this->getHttpHeaders();
    if(!empty($http_headers)) {
      foreach($http_headers as $name => $value) {
        header($name . ':' . $value);
      }
    }

    print($this->getBody());
  }





}
