<?php
namespace ResponseFormatter;
use \Base\ResponseFormatterInterface;

class JsonResponseFormatter implements ResponseFormatterInterface {
  private $format_mime_type = 'application/json';


  public function getFormatMimeType() {
    return $this->format_mime_type;
  }


  public function format(&$response) {
    $response_body = json_encode($response->getBody());

    $response->setBody($response_body);
    $response->setHttpHeader('Content-type', $this->format_mime_type);
  }
}
