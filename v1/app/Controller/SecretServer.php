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
    '404001' => 'Secret not found'
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
    return new \Response();
  }




  private function createNewSecret() {


  }


  private function getSecretByHash(String $hash) {

  }


}
