<?php

class Request {
  private $http_method;
  private $accept_mime_type;
  private $http_headers;

  public function __construnct() {
    $this->http_headers = apache_request_headers();
    $this->accept_mime_type = $this->getAcceptMimeType();
    $this->http_method = $this->getHttpMethod();
  }


  public function setHttpMethod($http_method) {
    $this->http_method = $http_method;
  }


  public function getHttpMethod() {
    if(empty($this->http_method)) {
      $this->setHttpMethod($_SERVER['REQUEST_METHOD']);
    }

    return $this->http_method;
  }


  public function isSupportedHttpMethod() {
    return (bool)($this->getHttpMethod() === 'POST' | $this->getHttpMethod() === 'GET');
  }


  public function setAcceptMimeType($accept_mime_type) {
    $this->accept_mime_type = $accept_mime_type;
  }


  public function getAcceptMimeType() {
    if(empty($this->accept_mime_type)) {
      if(isset($this->http_headers['Accept']) && !empty($this->http_headers['Accept'])) {
        if($this->http_headers['Accept'] == '*/*') {
          $this->setAcceptMimeType('application/json');
        } else {
          $this->setAcceptMimeType(explode(',', $this->http_headers['Accept']));
        }
      } else {
        $this->setAcceptMimeType('application/json');
      }
    }

    return $this->accept_mime_type;
  }


  public function setHttpHeaders($http_headers) {
    $this->http_headers = $http_headers;
  }


  public function getHttpHeaders() {
    if(empty($this->http_headers)) {
      $this->setHttpHeaders(apache_request_headers());
    }
    return $this->http_headers;
  }


  public function setProtocol($protocol) {
    $this->protocol = $protocol;
  }


  public function getProtocol() {
    if(empty($this->protocol)) {
      $this->setProtocol($_SERVER['SERVER_PROTOCOL']);
    }

    return $this->protocol;
  }


  public function isSupportedProtocol() {
    return (bool)(strpos($this->getProtocol(), 'HTTP') !== false);
  }


  public function getResponse(Route $route) {
    if($this->isValidRequestRoute($route)) {
      $response = $route->visit();
    }
  }


  public function isValidRequestRoute(Route $route) {
    if($route->hasClassInstance() && $route->hasMethod()) {
      if($this->isApiInterfaceMethod($route->getMethod()) {

      }
    }
  }




}
