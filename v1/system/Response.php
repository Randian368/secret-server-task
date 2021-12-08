<?php
use \ResponseFormatter\ResponseFormatterFactory as ResponseFormatterFactory;

/**
 * @purpose
 * Provides a structured interface for responding to api requests.
 * Specific response subtypes (e.g error response) may extend this class.
 */
class Response {
  public $body;

  private $http_status_code;
  private $http_headers = [];


  /** Outputs the information contained in this Response object for display,
   * including HTTP headers and HTTP reponse code as well as the response body.
   * @return null
   */
  public function output() {
    if(empty($this->getBody())) {
      $this->setHttpStatusCode(204);
    }

    $http_status_code = $this->getHttpStatusCode();
    http_response_code($http_status_code);

    header('Cache-Control: no-cache, max-age=0');

    $http_headers = $this->getHttpHeaders();
    if(!empty($http_headers)) {
      foreach($http_headers as $name => $value) {
        header($name . ':' . $value);
      }
    }

    print($this->getBody());
  }


  public function setBody($body) {
    $this->body = $body;
  }


  public function getBody() {
    return $this->body;
  }


  public function setHttpStatusCode($http_status_code) {
    $this->http_status_code = $http_status_code;
  }


  public function getHttpStatusCode() {
    return $this->http_status_code ?: 200;
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

}
