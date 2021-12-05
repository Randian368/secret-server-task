<?php
namespace ResponseFormatter;
use \Base\ResponseFormatterInterface;

class JsonResponseFormatter implements ResponseFormatterInterface {
  public function format(&$response) {
    $response = json_encode($response);
  }
}
