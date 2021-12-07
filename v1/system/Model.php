<?php

class Model {
  protected $db;

  private $hostname;
  private $username;
  private $password;
  private $database;
  private $port = '3306';

  public function __construct() {
    $this->hostname = CONFIG_DB_HOSTNAME;
    $this->username = CONFIG_DB_USERNAME;
    $this->database = CONFIG_DB_DATABASE;
    $this->password = CONFIG_DB_PASSWORD;
    if(defined('CONFIG_DB_PORT')) {
      $this->port = CONFIG_DB_PORT;
    }

    $this->connect();
  }


  public function connect() {
    $this->db = new \Db($this->hostname, $this->username, $this->password, $this->database, $this->port);
  }
}
