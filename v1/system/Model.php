<?php

class Model {
  protected $db;

  private $hostname;
  private $username;
  private $password;
  private $database;
  private $port = '3306';

  protected function connect() {
    $this->db = new \Db($this->hostname, $this->username, $this->password, $this->database, $this->port);
  }
}
