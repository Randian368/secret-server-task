<?php
namespace ResponseFormatter;
use \Base\ResponseFormatterInterface;

class JsonResponseFormatter implements ResponseFormatterInterface {
  public function format($response) {
    return json_encode($response);
  }
}
