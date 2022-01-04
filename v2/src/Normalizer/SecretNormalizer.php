<?php
namespace App\Normalizer;

use App\Entity\Secret;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Doctrine\Common\Annotations\AnnotationReader;

class SecretNormalizer implements ContextAwareNormalizerInterface, CacheableSupportsMethodInterface {
  private $object_normalizer;

  public function __construct() {
      $this->object_normalizer = new ObjectNormalizer(new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader())));
  }


  public function normalize($secret, string $format = null, array $context = []) {
    $data = $this->object_normalizer->normalize($secret, $format, $context);

    $data['createdAt'] = $this->toDateString($data['createdAt']);
    $data['expiresAt'] = $this->toDateString($data['expiresAt']);

    return $data;
  }


  public function supportsNormalization($data, string $format = null, array $context = []) {
    return $data instanceof Secret;
  }


  public function hasCacheableSupportsMethod() : bool {
    return __CLASS__ === static::class;
  }


  private function toDateString(int $timestamp) : string {
    $date_time = new \DateTime();
    $date_time->setTimestamp((int)$timestamp);
    $date_time->setTimezone(new \DateTimeZone('UTC'));

    return $date_time->format('Y-m-d\TH:i:s.v\Z'); // the specification showed Zulu time for display
  }

}
