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
  private $post;

  private $errors = [
    '404001' => 'Secret not found',
    '405001' => 'Invalid input',
    '500001' => 'Secret couldn\'t be created due to an unexpected internal error'
  ];


  public function __construct() {
    $this->secret = new Secret();
  }

  public function get($hash) : \Response{
    $this->secret->setByHash($hash);

    if(!$this->secret->getHash()) {
      $this->response = $this->getErrorResponse('404001');
    } else {
      $this->response = $this->secret->toResponse();
    }

    return $this->response;
  }


  public function post() : \Response {
    if($this->checkRequiredPostFields()) {
      $this->secret->create($this->post_data);

      if(empty($this->secret->getHash())) {
        $this->response = $this->getErrorResponse('500001');
      } else {
        $this->response = $this->secret->toResponse();
      }
    } else {
      $this->response = $this->getErrorResponse('405001');
    }
    return $this->response;
  }


  public function getResponse() :\Response {
    return $this->response ?: new \Response();
  }


  public function setPostData($post_data) : void {
    $this->post_data = $post_data;
  }


  private function checkRequiredPostFields() : bool {
    if(!$this->post_data) {
      if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $this->post_data = $_POST;
      } else {
        return false;
      }
    }
    if(!isset($this->post_data['secret']) || empty($this->post_data['secret'])) {
      return false;
    }
    if(!isset($this->post_data['expireAfterViews']) || empty($this->post_data['expireAfterViews']) || !is_numeric($this->post_data['expireAfterViews']) || $this->post_data['expireAfterViews'] <= 0) {
      return false;
    }
    if(!isset($this->post_data['expireAfter']) || empty($this->post_data['expireAfter']) || !is_numeric($this->post_data['expireAfter'])) {
      return false;
    }

    return true;
  }
}
