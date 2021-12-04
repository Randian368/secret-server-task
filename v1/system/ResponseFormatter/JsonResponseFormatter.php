<?php
namespace ResponseFormatter;

class JsonResponseFormatter implements ResponseFormatterInterface {
  public function format($response) {
    return json_encode($response);
  }
}
