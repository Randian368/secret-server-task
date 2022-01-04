<?php
namespace App\Mixin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Doctrine\Common\Annotations\AnnotationReader;


trait ApiResponseFormatterTrait {
  private $serializer;

  /**
   * Key is the format type, value is a context array.
   * @var array
   */
  private $supported_formats = [
    'json' => [],
    'xml'  => []
  ];

  /**
   * The format the request will be responded to; based on the Accept request header.
   * @var string
   */
  private $response_format;


  /**
   * Returns a Response object with content serialized based on the Request's Accept http header.
   * @method toResponse
   * @param  mixed   $content                        The content to be serialized.
   * @param  int     $http_status_code
   * @return Response
   */
  public function toResponse($content, $http_status_code = Response::HTTP_OK) : Response {
    $this->request = $this->container->get('request_stack')->getCurrentRequest();

    $this->response_format = $this->getPreferredSupportedFormat();

    $serializer = $this->getSupportedSerializer($content);
    $serializer_context = $this->supported_formats[$this->response_format];

    //$normalized_content = $serializer->normalize($content, null, [AbstractNormalizer::ATTRIBUTES => $included_fields]);
    $serialized_content = $serializer->serialize($content, $this->response_format, $serializer_context);

    $content_type = $this->request->getMimeType($this->response_format);

    $response = new Response();
    $response->setContent($serialized_content);
    $response->setStatusCode($http_status_code);
    $response->headers->set('Content-Type', $content_type);

    return $response;
  }


  /**
   * @method getSupportedSerializer
   * @return Serializer|void                 A Serializer object for formatting the response.
   */
  private function getSupportedSerializer($content) : ?Serializer {
    $normalizers = $this->getNormalizers($content);
    $encoders = $this->getEncoders($content);

    if(!empty($normalizers) && !empty($encoders)) {
      $serializer = new Serializer(
        $normalizers,
        $encoders
      );

      return $serializer;
    }
  }


  private function getNormalizers($data = null) {
    if(isset($data) && is_object($data)) {
      $reflection = new \ReflectionClass($data);
      $specific_normalizer = 'App\\Normalizer\\' . $reflection->getShortName() . 'Normalizer';

      if(class_exists($specific_normalizer)) {
        return [new $specific_normalizer()];
      }
    }

    return [new ObjectNormalizer(new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader())))];
  }


  private function getEncoders($data = null) : array {
    $format = $this->response_format ?: $this->getPreferredSupportedFormat();
    $encoder_class = "\\Symfony\\Component\\Serializer\\Encoder\\" . $this->ucFirstLcRest($format) . "Encoder";
    return [new $encoder_class()];
  }


  private function getPreferredSupportedFormat() : String {
    $response_format;
    $accepted_content_types = $this->request->getAcceptableContentTypes();

    foreach($accepted_content_types as $content_type) {
      $format = $this->request->getFormat($content_type);

      if($this->isSupportedFormat($format)) {
        $response_format = $format;
        break;
      }
    }

    return $response_format ?: $this->getDefaultFormat();
  }


  private function getDefaultFormat() {
    return array_keys($this->supported_formats)[0];
  }


  private function isSupportedFormat($format) {
    return (false !== in_array(strtolower($format), array_keys($this->supported_formats)));
  }


  private function ucFirstLcRest($text) {
    return strtoupper(substr($text, 0, 1)) . strtolower(substr($text, 1));
  }

}
