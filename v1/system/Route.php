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

  public function setArg($arg) {
    $this->arg = $arg;
  }

  public function getArg() {
    return $this->arg;
  }
}