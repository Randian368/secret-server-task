<?php
declare(strict_types = 1);

namespace Controller;

use \Base\ApiControllerInterface;
use \Model\Secret as Secret;


class SecretServer implements ApiControllerInterface {
  private $response;
  private $secret;

  public function __construct() {
    $this->secret = new Secret();
  }

  public function get($hash) : \Response{
    return new \Response();
  }


  public function post() : \Response {
    return new \Response();
  }


  private function createNewSecret() {


  }


  private function getSecretByHash(String $hash) {

  }


}
