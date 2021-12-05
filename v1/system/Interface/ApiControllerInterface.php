<?php
declare(strict_types = 1);

namespace \Controller\Interface;

interface ApiControllerInterface {
  
  public function get($id) : \Response;

  public function post() : \Response;

}
