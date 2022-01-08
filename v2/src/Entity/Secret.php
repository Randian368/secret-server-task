<?php

namespace App\Entity;

use App\Repository\SecretRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SecretRepository::class)
 */
class Secret
{

  /**
   * @Ignore()
   */
  private $cipher_algorithm = 'aes-128-cbc';

  /**
   * @Ignore()
   */
  private $passphrase = 'dW5zbGljZWQgcHVzaGNoYWlyIHNja';

  /**
   * @Ignore()
   */
  private $initialization_vector = '34e2b514acd2cbc2';

  /**
   * @ORM\Id()
   * @ORM\Column(type="string", length=40)
   * @Assert\NotBlank()
   * @Assert\Type("string")
   */
  private $hash;

  /**
   * @ORM\Column(type="text", name="secretText")
   * @Assert\NotBlank()
   * @Assert\Type("string")
   */
  private $secretText;

  /**
   * @ORM\Column(type="integer", name="createdAt")
   * @Assert\NotBlank()
   * @Assert\Type("int")
   */
  private $createdAt;

  /**
   * @ORM\Column(type="integer", name="expiresAt")
   * @Assert\NotBlank()
   * @Assert\Type("int")
   */
  private $expiresAt;

  /**
   * @ORM\Column(type="integer", name="expiresAfterMinutes")
   * @Assert\NotBlank()
   * @Assert\Type("int")
   * @Ignore()
   */
  private $expiresAfterMinutes;

  /**
   * @ORM\Column(type="integer", name="expiresAfterViews")
   * @Assert\NotBlank()
   * @Assert\Type("int")
   * @Ignore()
   */
  private $expiresAfterViews;

  /**
   * @ORM\Column(type="integer", name="remainingViews")
   * @Assert\NotBlank()
   * @Assert\Type("int")
   */
  private $remainingViews;


  public function __construct(array $data) {
    if(!empty($data)) {
      $this->setHash();
      $this->setSecretText($data['secret']);
      $this->setCreatedAt(time());
      $this->setExpiresAt(
        $this->getExpireTime($this->createdAt, $data['expireAfter'])
      );
      $this->setExpiresAfterMinutes($data['expireAfter']);
      $this->setExpiresAfterViews($data['expireAfterViews']);
      $this->setRemainingViews((int)$data['expireAfterViews']);
    }
  }


  public function getHash(): ?string
  {
      return $this->hash;
  }

  public function setHash(string $hash = ''): self {
    if(!$hash) {
      $hash = $this->createHashString();
    }
    $this->hash = $hash;

    return $this;
  }

  public function getSecretText(): ?string
  {
      return $this->decryptSecretText($this->secretText);
  }

  public function setSecretText(string $secretText): self
  {
      $this->secretText = $this->encryptSecretText($secretText);

      return $this;
  }

  public function getCreatedAt(): ?int
  {
      return $this->createdAt;
  }

  public function setCreatedAt(int $createdAt): self
  {
      $this->createdAt = $createdAt;

      return $this;
  }

  public function getExpiresAt(): ?int
  {
      return $this->expiresAt;
  }

  public function setExpiresAt(int $expiresAt): self
  {
      $this->expiresAt = $expiresAt;

      return $this;
  }

  public function getExpiresAfterMinutes(): ?int
  {
      return $this->expiresAfterMinutes;
  }

  public function setExpiresAfterMinutes(int $expiresAfterMinutes): self
  {
      $this->expiresAfterMinutes = $expiresAfterMinutes;

      return $this;
  }

  public function getExpiresAfterViews(): ?int
  {
      return $this->expiresAfterViews;
  }

  public function setExpiresAfterViews(int $expiresAfterViews): self
  {
      $this->expiresAfterViews = $expiresAfterViews;

      return $this;
  }

  public function getRemainingViews(): ?int
  {
      return $this->remainingViews;
  }

  public function setRemainingViews(int $remainingViews): self
  {
      $this->remainingViews = $remainingViews;

      return $this;
  }

  private function encryptSecretText($secret_text) : string {
    return openssl_encrypt($secret_text, $this->cipher_algorithm, $this->passphrase, 0, $this->initialization_vector);
  }


  private function decryptSecretText($encrypted_secret_text) : string {
    return openssl_decrypt($encrypted_secret_text, $this->cipher_algorithm, $this->passphrase, 0, $this->initialization_vector);
  }


  private function createHashString() : string {
    return bin2hex(random_bytes(20));
  }


  /**
   * @Ignore()
   */
  private function getExpireTime($created_at, $expires_after_minutes) : int {
    return $created_at + ($expires_after_minutes * 60);
  }
}
