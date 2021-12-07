<?php
declare(strict_types = 1);

namespace Base;

interface ModelInterface {

  public function toResponse() :\Response;

}
