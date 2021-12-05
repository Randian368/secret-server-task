<?php

class Route {
  protected $class;
  protected $method;
  protected $arg;

  public function getClass() {
    return $this->class;
  }


  public function setClass(Object $class) {
    $this->class = $class;
  }

  public function hasClassInstance() {
    $class = $this->getClass();
    return (bool)($class !== null && !empty($class) && gettype($class) == 'object');
  }


  public function getMethod() {
    return $this->method;
  }


  public function setMethod(String $method) {
    $this->method = $method;
  }


  public function hasMethod() {
    $method = $this->getMethod();
    return (bool)($method !== null && !empty($method));
  }


  public function setArgument($arg) {
    $this->arg = $arg;
  }


  public function getArgument() {
    return $this->arg;
  }


  public function visit() {
    $class = $this->getClass();
    $method = $this->getMethod();
    $arg = $this->getArgument();

    if($this->hasClassInstance() && $this->hasMethod()) {
      if($arg) {
        $response = $class->$method($arg);
      } else {
        $response = $class->$method();
      }
      return $response;
    }
  }
  
}
