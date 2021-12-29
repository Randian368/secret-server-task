<?php

namespace App\Entity;

use App\Repository\SecretRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SecretRepository::class)
 */
class Secret
{

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=40)
     */
    private $hash;

    /**
     * @ORM\Column(type="text", name="secretText")
     */
    private $secretText;

    /**
     * @ORM\Column(type="integer", name="createdAt")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer", name="expiresAt")
     */
    private $expiresAt;

    /**
     * @ORM\Column(type="integer", name="expiresAfterMinutes")
     */
    private $expiresAfterMinutes;

    /**
     * @ORM\Column(type="integer", name="expiresAfterViews")
     */
    private $expiresAfterViews;

    /**
     * @ORM\Column(type="integer", name="remainingViews")
     */
    private $remainingViews;

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getSecretText(): ?string
    {
        return $this->secretText;
    }

    public function setSecretText(string $secretText): self
    {
        $this->secretText = $secretText;

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
}
