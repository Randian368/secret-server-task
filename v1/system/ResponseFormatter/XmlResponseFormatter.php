<?php
namespace ResponseFormatter;
use \Base\ResponseFormatterInterface;

class XmlResponseFormatter implements ResponseFormatterInterface {
  private $format_mime_type = 'application/xml';


  public function getFormatMimeType() {
    return $this->format_mime_type;
  }


  public function format(&$response) {
    $response_body = $response->getBody();

    if($response_body) {
      $response_body_array = json_decode(json_encode($response_body), JSON_OBJECT_AS_ARRAY);
      $response_body = $this->xml_encode($response_body_array);

      $response->setBody($response_body);
      $response->setHttpHeader('Content-type', $this->format_mime_type);
    }
  }


  /* source: https://www.darklaunch.com/php-xml-encode-using-domdocument-convert-array-to-xml-json-encode.html */
  private function xml_encode($mixed, $domElement=null, $DOMDocument=null) {
    if (is_null($DOMDocument)) {
      $DOMDocument = new \DOMDocument;
      $DOMDocument->formatOutput = true;
      $this->xml_encode($mixed, $DOMDocument, $DOMDocument);

      return $DOMDocument->saveXML();
    }
    else {
      if (is_array($mixed)) {
        foreach ($mixed as $index => $mixedElement) {
          if (is_int($index)) {
            if ($index === 0) {
              $node = $domElement;
            }
            else {
              $node = $DOMDocument->createElement($domElement->tagName);
              $domElement->parentNode->appendChild($node);
            }
          }
          else {
            $plural = $DOMDocument->createElement($index);
            $domElement->appendChild($plural);
            $node = $plural;
            if (!(rtrim($index, 's') === $index)) {
              $singular = $DOMDocument->createElement(rtrim($index, 's'));
              $plural->appendChild($singular);
              $node = $singular;
            }
          }

          $this->xml_encode($mixedElement, $node, $DOMDocument);
        }
      }
      else {
        $domElement->appendChild($DOMDocument->createTextNode($mixed));
      }
    }
  }
}
