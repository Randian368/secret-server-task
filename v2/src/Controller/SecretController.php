<?php
namespace App\Controller;

use App\Entity\Secret;
use App\Mixin\ApiResponseFormatterTrait;

use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/secret")
 */
class SecretController extends AbstractController {
  use ApiResponseFormatterTrait;

  private $doctrine; // i have no clue if this is how doctrine is used in controllers

  private $errors = [
    '404001' => 'Secret not found',
    '405001' => 'Invalid input',
    '500001' => 'Unexpected internal error',
    '500002' => 'Secret couldn\'t be created due to an unexpected internal error'
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
      if(($remaining_views = $secret->getRemainingViews()) >= 1 && !$this->isExpired($secret)) {
        $secret->setRemainingViews($remaining_views - 1);

        try {
          $response = $this->toResponse($secret);
        } catch(\Throwable $e) { // issue: exception thrown from toResponse never reaches this, because a kernel.exception event subscriber returns a response prior this point
          $error_message = $this->errors['500001'] . ': ' . $e->getMessage();
          $response = $this->toResponse($error_message, 500);
        }

        $this->doctrine->getManager()->flush(); // only decreasing view count in the database if no error happened
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
  public function createSecret(Request $request, ValidatorInterface $validator): Response {
    $post = $request->request->all();
    if($this->hasRequiredFields($post)) {
      $secret = new Secret($post);

      if(count($validator->validate($secret)) == 0) {
        $this->doctrine->getManager()->persist($secret);
        $this->doctrine->getManager()->flush();

        $response = $this->readSecret($secret->getHash());
      } else {
       $response = $this->toResponse($this->errors['405001'], 405);
      }
    } else {
      $response = $this->toResponse($this->errors['405001'], 405);
    }

    return $response;
  }


  private function getSecretByHash($hash) : ?Secret {
    return $this->doctrine->getRepository('App:Secret')->find($hash);
  }


  private function getFormattedDateTime($timestamp) : string {
    $date_time = new \DateTime();
    $date_time->setTimestamp((int)$timestamp);
    $date_time->setTimezone(new \DateTimeZone('UTC'));
    return $date_time->format('Y-m-d\TH:i:s.v\Z'); // the specification showed Zulu time for display
  }


  private function hasRequiredFields($post) : bool {
    $required_post_fields = [
      'secret',
      'expireAfterViews',
      'expireAfter'
    ];

    foreach($required_post_fields as $field_name) {
      if(!isset($post[$field_name])) {
        return false;
      }
    }
    return true;
  }


  private function isExpired($secret) : bool {
    return (bool)(time() > $secret->getExpiresAt());
  }

}
