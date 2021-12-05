<?php

class Route {
  protected $class;
  protected $method;
  protected $arg;

  public function getClass() {
    return $this->method;
  }

  public function setClass(Object $class) {
    $this->class = $class;
  }

  public function getMethod() {
    return $this->method;
  }

  public function setMethod(String $method) {
    $this->method = $method;
  }

  public function setArgument($arg) {
    $this->arg = $arg;
  }

  public function getArgument() {
    return $this->arg;
  }

  public function visit() {
    if($this->arg) {
      $response = ($this->class)->($this->method)($this->arg);
    } else {
      $response = ($this->class)->($this->method)();
    }
    return $response;
  }
}
