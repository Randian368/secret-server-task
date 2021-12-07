<?php
declare(strict_types = 1);

namespace Concern;

trait ControllerTrait {


  protected function getError($inner_code) {
    $error = [
      'http_status_code' => substr($inner_code, 0, 3),
      'message'          => $this->errors[$inner_code]
    ];

    return $error;
  }


  protected function getErrorResponse($inner_code) {
    $error = $this->getError($inner_code);
    return new \Response\ErrorResponse($error['http_status_code'], $error['message']);
  }
}
