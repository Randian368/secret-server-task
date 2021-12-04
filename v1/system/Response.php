<?php
use \ResponseFormatter\ResponseFormatterFactory as ResponseFormatterFactory;

class Response {
  private $formatter;
  private $head;
  private $body;

  public function __construct($response_body) {
    $this->formatter = (new ResponseFormatterFactory())->createFormatter();
    $this->body = $response_body;
  }


  public function get() {
    return $this->formatter->format($this->body);
  }
}
