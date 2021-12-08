<?php

use \Factory\ResponseFormatterFactory;
use \Response\ErrorResponse;
use \Helper\StringHelper;

/**
 *  @purpose
 *  Provide encapsulation for functionalities that concern an api request;
 *  such as validating the request route, handling request-related errors (e.g bad request routes, or no retrievable response)
 *  and ensuring that every api request results in a Response object
 *
 */
class Request {
  private $http_method;
  private $accept_mime_type;
  private $http_headers;


  public function __construct() {
    $this->http_headers       = $this->getHttpHeaders();
    $this->accept_mime_type   = $this->getAcceptMimeType();
    $this->http_method        = $this->getHttpMethod();
  }


  public function getResponse(Route $route) {
    if($this->isValidRequestRoute($route)) {
      $response = $route->visit();
    } else {
      $response = new \Response\ErrorResponse(400, 'Bad request. The requested endpoint is malformatted or unaccessible to this client.');
    }

    if(!($response instanceof \Response)) {
      $response = $this->getExceptionResponse();
    }
    return $response;
  }


  /** Gets the default, general ErrorResponse to output in case of an unexpected error.
   * @method getExceptionResponse
   * @return Response
   */
  public function getExceptionResponse() {
    return new \Response\ErrorResponse(500, 'A internal error occurred during the execution of this request.');
  }


  /**
   *  @method isValidRequestRoute
   *  @param Route $route
   *  @return bool                   The api only services through the predefined controller interface methods.
   *                                 If the Request is not to a controller class and one of its controller interface methods, isValidRequestRoute returns false
   */
  public function isValidRequestRoute(Route $route) {
    if($route->hasClassInstance() && $route->hasMethod()) {
      if($route->getClass() instanceof \Base\ControllerInterface) {
        if(method_exists('\Base\ControllerInterface', $route->getMethod())) {
          return true;
        }
      }
    }
    return false;
  }


  public function isSupportedHttpMethod() {
    return (bool)($this->getHttpMethod() === 'POST' | $this->getHttpMethod() === 'GET');
  }


  public function setAcceptMimeType($accept_mime_type) {
    $this->accept_mime_type = $accept_mime_type;
  }


  /** Retrieves the Request's Accepted mime type, if empty, attempts to set it. Default / fallback is application/json.
   *  @method getAcceptMimeType
   *  @return string|array            The value of the Accept HTTP header, string if only one type is accepted by the client, array if multiple
   */
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


  public function isSupportedProtocol() {
    return (bool)(strpos($this->getProtocol(), 'HTTP') !== false);
  }


  public function setHttpMethod($http_method) {
    $this->http_method = $http_method;
  }


  /** Retrieves this Request's HTTP method, if empty, attempts to set it
   *  @method getHttpMethod
   *  @return string                   e.g. HTTP/1.1
   */
  public function getHttpMethod() {
    if(empty($this->http_method)) {
      $this->setHttpMethod($_SERVER['REQUEST_METHOD']);
    }
    return $this->http_method;
  }


  /** Sets the HTTP headers that the Response object should take into consideration when creating a Response
   *  @method setHttpHeaders
   *  @param array $http_headers       a set of HTTP header key-valuze pairs for this Request to consider when responsing, formatted like the return value of apache_request_headers()
   *  @return null
   */
  public function setHttpHeaders($http_headers) {
    if(!empty($http_headers)) {
      $http_headers = $this->normalizeArrayKeyCase($http_headers);
    }
    $this->http_headers = $http_headers;
  }


  public function getHttpHeaders() {
    if(empty($this->http_headers)) {
      $http_headers = function_exists('apache_request_headers') ? apache_request_headers() : [];
      $this->setHttpHeaders($http_headers);
    }
    return $this->http_headers;
  }


  /** Since HTTP headers are case-insensitive (any case combination should be accepted as valid) the HTTP header array must be transformed to an uniform key case format.
    * @method normalizeArrayKeyCase
    * @param array $http_headers
    * @return array
    */
  private function normalizeArrayKeyCase($http_headers) {
    $normalized_http_header_names = array_map(function($name) {
      $normalized_name = StringHelper::ucfirstLcrest($name);
      if($name !== $normalized_name) {
        return $normalized_name;
      }
      return $name;
    }, array_keys($http_headers));

    return array_combine($normalized_http_header_names, array_values($http_headers));
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

}
