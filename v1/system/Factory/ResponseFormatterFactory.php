<?php
namespace ResponseFormatter;
use \Helper\StringHelper as StringHelper;

class ResponseFormatterFactory {

  public function createFormatter($accept_mime_type) {
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

    throw new \Exception('The requested response format is not supported!');
  }


  private function getFromatterClassInstance($accept_mime_type) {
    if($subtype = $this->getSubtype($accept_mime_type)) {
      $class_name = $this->getFormatterClassName($subtype);

      if(class_exists($class_name)) {
        return new $class_name();
      }
    }
  }


  private function getSubtype($accept_mime_type) {
    if(preg_match('/[^;\r\n\s]+(?:\\\\|\/)([^;\r\n\s]+)(?:$|[;\r\n\s])/', $accept_mime_type, $subtype) && isset($subtype[1])) {
      return $subtype[1];
    }
    return false;
  }


  private function getFormatterClassName($subtype) {
    $formatter_class_name = '';

    $type_name = StringHelper::format_as_class_name($subtype);

    if($type_name) {
      $formatter_class_name = 'ResponseFormatter\\' . $type_name . 'ResponseFormatter';
      return $formatter_class_name;
    }
  }
}
