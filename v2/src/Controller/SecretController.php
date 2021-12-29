<?php
namespace App\Controller;

use App\Entity\Secret;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\HttpFoundation\XmlResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/secret")
 */
class SecretController  extends AbstractController {
  private $doctrine; // i have np clue if this is how doctrine is used in controllers

  public function __construct(ManagerRegistry $doctrine) {
    $this->doctrine = $doctrine;
  }

  /**
   * @Route("/{hash}", name="secret_get", methods={"GET"})
   */
  public function readSecret(string $hash): Response {
    $secret =  $this->getSecretByHash($hash);
    dump($secret);
    if(!$secret) {
      throw $this->createNotFoundException(
          'No product found for id '. $hash
      );
    }

    $response =  new Response('Hello '.$hash, Response::HTTP_OK);
    return $response;

  }


  /**
   * @Route(name="secret_post", methods={"POST"})
   */
  public function createSecret(ValidatorInterface $validator): Response {

  }


  private function getSecretByHash($hash) : ?Secret {
    return $this->doctrine->getRepository('App:Secret')->find($hash);
  }

}
