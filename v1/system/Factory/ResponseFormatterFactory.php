<?php
namespace Factory;
use \Helper\StringHelper as StringHelper;

/**
 * @purpose
 * Determine which response formatter to use based on mime type
 * and return a class instance that implements ResponseFormatterInterface.
 */
class ResponseFormatterFactory {

  public function create($accept_mime_type) {
    if(is_array($accept_mime_type) && count($accept_mime_type) > 1) {
      $mime_types = $accept_mime_type;
      foreach($mime_types as $accept_mime_type) {
        if($class_instance = $this->getFromatterClassInstance($accept_mime_type)) {
          return $class_instance;
        }
      }
      unset($accept_mime_type);
    } else if(is_array($accept_mime_type) && count($accept_mime_type) == 1) {
      $accept_mime_type = $accept_mime_type[0];
    }

    if(!empty($accept_mime_type)) {
      if($class_instance = $this->getFromatterClassInstance($accept_mime_type)) {
        return $class_instance;
      }
    }

    return $this->getFormatterClassInstance('application/json'); // fallback to json if the Accept HTTP heder didn't contain any supported mime types
  }


  private function getFromatterClassInstance($accept_mime_type) {
    if($subtype = $this->getSubtype($accept_mime_type)) {
      $class_name = $this->getFormatterClassName($subtype);

      if(class_exists($class_name)) {
        return new $class_name();
      }
    }
  }


  /** Returns the subtype part of a mime type string.
   * @method getSubtype
   * @param string $accept_mime_type                A valid mime type string value, e.g.: text/html
   * @return bool|string                            False if no subtype found, otherwise the subtype, e.g.: html
   */
  private function getSubtype($accept_mime_type) {
    if(preg_match('/[^;\r\n\s]+(?:\\\\|\/)([^;\r\n\s]+)(?:$|[;\r\n\s])/', $accept_mime_type, $subtype) && isset($subtype[1])) {
      return $subtype[1];
    }
    return false;
  }


  /** Gets a ResponseFormatter's class name based on mime subtype. Class names should adhere to the following pattern:
   * subtype name without special characters; first letter of each word uppercase, rest of the letters lowercase
   * postfixed by 'ResponseFormatter'
   * @method getFormatterClassName
   * @param string $subtype                       The subtype part of a mime type string
   * @return string
   * */
  private function getFormatterClassName($subtype) {
    $formatter_class_name = '';

    $type_name = StringHelper::format_as_class_name($subtype);

    if($type_name) {
      $formatter_class_name = 'ResponseFormatter\\' . $type_name . 'ResponseFormatter';
      return $formatter_class_name;
    }
  }
}
