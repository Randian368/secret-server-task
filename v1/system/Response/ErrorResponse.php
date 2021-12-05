<?php

class ErrorResponse extends Response {
  private $errors = [
    [
      'code'        => 400,
      'inner_code'  => 400001,
      'name'        => 'Bad Request',
      'message'     => ''
    ]
  ]

  public function construct($inner_code, $custom_addition => []) {
    $error = $self->getErrorByInnerCode($inner_code);

    $error_response = new Response();
    $response->setHTTPStatusCode($error['code']);

    $body = array_merge($error, $custom_addition);
    $response->setBody($body);

    return $response;
  }


  protected function getErrorByInnerCode($inner_code) {
    return $this->errors[array_search($inner_code, array_column($this->errors, 'inner_code'))];
  }





}
