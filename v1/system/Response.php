<?php
use \Request;
use \ResponseFormatter\ResponseFormatterFactory as ResponseFormatterFactory;

class Response {
  private $request;
  private $formatter;
  private $head;
  private $body;

  public function __construct(Request $request) {
    $this->request = $request;
  }



}
