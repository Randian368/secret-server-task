<?php
declare(strict_types = 1);

namespace Concern;

trait ControllerTrait {
  public function getResponse() : \Response {
    return $this->response === null ? new \Response() : $this->response;
  }


  protected function getError($inner_code) {
    $error = [
      'http_status_code' => substr($inner_code, 0, 3),
      'message'          => $this->errors[$inner_code]
    ];

    return $error;
  }


  protected function setErrorResponse($inner_code) {
    $error = $this->getError($inner_code);
    $this->response = new \Response\ErrorResponse($error['http_status_code'], $error['message']);
  }
}
