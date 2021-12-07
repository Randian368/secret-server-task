<?php
declare(strict_types = 1);

namespace Controller;

use \Base\ControllerInterface;
use \Concern\ControllerTrait as ControllerTrait;
use \Model\Secret as Secret;


class SecretServer implements ControllerInterface {
  use ControllerTrait;

  private $response;
  private $secret;

  private $errors = [
    '404001' => 'Secret not found',
    '405001' => 'Invalid input'
  ];


  public function __construct() {
    $this->secret = new Secret();
  }

  public function get($hash) : \Response{
    $this->response = $this->secret->getByHash($hash);

    if(!$this->response) {
      $this->setErrorResponse('404001');
    }

    return $this->getResponse();
  }


  public function post() : \Response {
    if($this->checkRequiredPostFields()) {

    } else {
      $this->setErrorResponse('405001');
    }
    return $this->getResponse();
  }


  private function checkRequiredPostFields() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      return true;
    }
    return false;
  }




  private function createNewSecret() {


  }


  private function getSecretByHash(String $hash) {

  }


}
