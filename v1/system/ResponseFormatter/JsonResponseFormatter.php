<?php
namespace ResponseFormatter;

class JsonResponseFormatter implements Interface\ResponseFormatterInterface {
  public function format($response) {
    return json_encode($response);
  }
}
