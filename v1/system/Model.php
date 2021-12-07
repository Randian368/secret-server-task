<?php

class Model {
  protected $db;

  private $hostname;
  private $username;
  private $password;
  private $database;
  private $port = '3306';

  public function __construct() {
    $this->connect();
  }

  public function connect() {
    $this->db = new \Db($this->hostname, $this->username, $this->password, $this->database, $this->port);
    $this->db->query('lol');
  }
}
