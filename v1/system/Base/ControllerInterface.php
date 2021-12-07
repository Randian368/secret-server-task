<?php
declare(strict_types = 1);

namespace Base;

interface ControllerInterface {

  public function get($id) : \Response;

  public function post() : \Response;

  public function getResponse() :\Response;

}
