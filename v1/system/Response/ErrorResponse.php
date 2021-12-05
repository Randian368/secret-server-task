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

  public function getErrorResponse($inner_code) {
    $error = $this->getErrorByInnerCode($inner_code);

    $error_response = new Response();
    $response->setHTTPStatusCode($error['code']);

    $formatter = new ResponseFormatter()
    $response->setBody($formatted_error_body);
  }


  public function




}
