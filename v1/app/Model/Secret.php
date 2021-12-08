<?php
declare(strict_types = 1);

namespace Model;

use \Base\ModelInterface;
use \Defuse\Crypto\Crypto;

class Secret extends \Model implements ModelInterface {
  private $cipher_algorithm = 'aes-128-cbc';
  private $passphrase = 'dW5zbGljZWQgcHVzaGNoYWlyIHNja';
  private $initialization_vector = '34e2b514acd2cbc2';
  private $date_display_format = 'Y-m-d\TH:i:s.v\Z';

  public $hash = '';
  public $secretText = '';
  public $createdAt = '';
  public $expiresAt = '';
  public $remainingViews = 0;


  public function toResponse() : \Response {
    $response = new \Response();
    $response->setBody($this);

    return $response;
  }


  public function create($data) {
    $hash = $this->createHash();
    $secret_text = $this->encryptSecretText($data['secret']);
    $created_at = time();
    $expires_at = $this->getExpireTime($created_at, $data['expireAfter']);
    $remaining_views = (int)$data['expireAfterViews'];

    $insert = "INSERT INTO `secret` SET `hash` = '" . $this->db->escape($hash) . "', `secretText` = '" . $this->db->escape($secret_text) . "', `createdAt` = '" . $created_at . "', `expiresAt` = '" . $expires_at . "', `expiresAfterMinutes` = '" . (int)$data['expireAfter'] . "', `expiresAfterViews` = '" . $remaining_views . "', `remainingViews` = '" . $remaining_views . "'";
    $this->db->query($insert);

    if($this->db->countAffected()) {
      $this->setByHash($hash, false);
    }
  }


  public function setByHash(String $hash, $subtract_remaining_views = true) : void {
    $select = "SELECT * FROM `secret` WHERE `hash` = '" . $this->db->escape($hash) . "' AND `remainingViews` > 0";
    $result = $this->db->query($select)->row;

    if(!empty($result)) {
      if(!$this->isExpired($result)) {
        if($subtract_remaining_views) $this->subtractRemainingViews($hash);

        $this->hash = $hash;
        $this->secretText = $this->decryptSecretText($result['secretText']);
        $this->createdAt = date($this->date_display_format, (int)$result['createdAt']);
        $this->expiresAt = date($this->date_display_format, (int)$result['expiresAt']);
        $this->remainingViews = $subtract_remaining_views ? (int)$result['remainingViews'] - 1 : (int)$result['remainingViews'];
      }
    }
  }


  public function getHash() {
    return $this->hash;
  }


  private function createHash() : string {
    return bin2hex(random_bytes(20));
  }


  private function encryptSecretText($secret_text) : string {
    return openssl_encrypt($secret_text, $this->cipher_algorithm, $this->passphrase, 0, $this->initialization_vector);
  }


  private function decryptSecretText($encrypted_secret_text) : string {
    return openssl_decrypt($encrypted_secret_text, $this->cipher_algorithm, $this->passphrase, 0, $this->initialization_vector);
  }


  private function getExpireTime($created_at, $expires_after_minutes) : int {
    return $created_at + ($expires_after_minutes * 60);
  }


  private function isExpired($result) : bool {
    return (bool)($result['expiresAfterMinutes'] > 0 && $result['expiresAt'] < time());
  }


  private function subtractRemainingViews($hash) : int {
    $update = "UPDATE `secret` SET `remainingViews` = (CAST(`remainingViews` AS INT) - 1) WHERE `hash` = '" . $this->db->escape($hash) . "'";
    $this->db->query($update);
    return $this->db->countAffected();
  }


}
