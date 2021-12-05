<?php
namespace Model;

class Secret extends \Model{
  public $hash = '';
  public $secretText = '';
  public $createdAt = '';
  public $expiresAt = '';
  public $remainingViews = 0;

  public function getByHash(String $hash) {
    if(!$this->db->isConnected()) $this->connect();


  }
}
