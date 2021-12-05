<?php
namespace Response;

class ErrorResponse extends \Response {
  private $errors = [
    [
      'code'        => 400,
      'inner_code'  => 400001,
      'name'        => 'Bad Request',
      'message'     => ''
    ],
    [
      'code'        => 500,
      'inner_code'  => 500001,
      'name'        => 'Internal Error',
      'message'     => 'The server cannot handle this request right now.'
    ]
  ];

  public function __construct($inner_code, $custom_addition = []) {
    $error = $this->getErrorByInnerCode($inner_code);

    $this->setHttpStatusCode($error['code']);
    $this->setBody(array_merge($error, $custom_addition));
  }


  protected function getErrorByInnerCode($inner_code) {
    return $this->errors[array_search($inner_code, array_column($this->errors, 'inner_code'))];
  }





}
