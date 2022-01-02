<?php
namespace App\Controller;

use App\Entity\Secret;
use App\Mixin\ApiResponseFormatterTrait;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/secret")
 */
class SecretController extends AbstractController {
  use ApiResponseFormatterTrait;

  private $doctrine; // i have no clue if this is how doctrine is used in controllers

  private $errors = [
    '404001' => 'Secret not found',
    '405001' => 'Invalid input',
    '500001' => 'Secret couldn\'t be created due to an unexpected internal error'
  ];


  public function __construct(ManagerRegistry $doctrine) {
    $this->doctrine = $doctrine;
  }

  /**
   * @Route("/{hash}", name="secret_get", methods={"GET"})
   */
  public function readSecret(string $hash): Response {
    $secret =  $this->getSecretByHash($hash);
    if($secret) {
      if(($remaining_views = $secret->getRemainingViews()) >= 1) {
        $secret->setRemainingViews($remaining_views - 1);
        $this->doctrine->getManager()->flush();

        $response = $this->toResponse($secret);
      }
    }

    if(!isset($response)) {
      $response = $this->toResponse($this->errors['404001'], Response::HTTP_NOT_FOUND);
    }

    return $response;
  }


  /**
   * @Route(name="secret_post", methods={"POST"})
   */
  public function createSecret(ValidatorInterface $validator): Response {


    return $response;
  }


  private function getSecretByHash($hash) : ?Secret {
    return $this->doctrine->getRepository('App:Secret')->find($hash);
  }

}
