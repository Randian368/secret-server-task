<?php
namespace App\Mixin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


trait ApiResponseFormatterTrait {
  private $serializer;
  private $supported_formats = [
    'json',
    'xml'
  ];

  /**
   * Returns a Response object with content serialized based on the Request's Accept http header.
   * @method toResponse
   * @param  mixed   $content                        The content to be serialized.
   * @param  int     $http_status_code
   * @return Response
   */
  public function toResponse($content, $http_status_code = Response::HTTP_OK) : Response {
    $response = new Response();
    $serializer = $this->getSupportedSerializer();

    $response->setContent($this->serialize($content));
    $response->setStatusCode($http_status_code);

    $content_type =  $request->getContentType($this->getPreferredSupportedFormat());
    $response->headers->set('Content-type', $content_type);

    return $response;
  }


  private function getSupportedSerializer() : ?Serializer {
    $normalizers = $this->getNormalizers();
    $encoders = $this->getEncoders();

    if(!empty($normalizers) && !empty($encoders)) {
      $this->serializer = new Serializer(
        $this->getNormalizers(),
        $this->getEncoders()
      );

      return $serializer;
    }
  }


  private function getNormalizers() {
    return [new ObjectNormalizer()];
  }


  private function getEncoders() : array {
    $format_name = $this->ucFirstLcRest($this->getPreferredSupportedFormat());
    return ["\\Symfony\\Component\\Serializer\\Encoder\\${format_name}Encoder"];
  }


  private function getPreferredSupportedFormat(Request $request) : String {
    $preferred_supported_format;

    $accepted_content_types = $request->getAcceptableContentTypes();

    foreach($accepted_content_types as $content_type) {
      $format = $request->getFormat($content_type);

      if($this->isSupportedFormat($format)) {
        $preferred_supported_format = $format;
        break;
      }
    }

    return $this->supported_formats[0];
  }


  private function isSupportedFormat($format) {
    return in_array(strtolower($format), $this->supported_formats);
  }


  private function ucFirstLcRest($text) {
    return strtoupper(substr($text, 0, 1)) . strtolower(substr($text, 1));
  }

}
