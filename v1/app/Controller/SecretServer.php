<?php
declare(strict_types = 1);

namespace Controller;

use \Model\Secret;

class SecretServer implements Interface\ApiControllerInterface {
  private $response;

  public function __construct() {
    $this->model = new \Model\Secret;
  }

  public function get($hash) : \Response{

  }


  public function post() : \Response {

  }


  private function createNewSecret() {


  }


  private function getSecretByHash(String $hash) {

  }


}
