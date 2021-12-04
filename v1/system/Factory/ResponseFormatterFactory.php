<?php
namespace ResponseFormatter;
use \Helper\StringHelper as StringHelper;

class ResponseFormatterFactory {

  public function createFormatter() {
    $mime_type = '';
    $request_headers = apache_request_headers();

    if(isset($request_headers['Accept']) && !empty($request_headers['Accept'])) {
      if($request_headers['Accept'] == '*/*') {
        $mime_type = 'application/json';
      } else {
        $mime_type = explode(',', $request_headers['Accept']);
      }
    } else {
      $mime_type = 'application/json';
    }

    if(is_array($mime_type) && count($mime_type) > 1) {
      $mime_types = $mime_type;
      foreach($mime_types as $mime_type) {
        if($class_instance = $this->getFromatterClassInstance($mime_type)) {
          return $class_instance;
        }
      }
      unset($mime_type);
    } else if(is_array($mime_type) && count($mime_type) == 1) {
      $mime_type = $mime_type[0];
    }

    if(!empty($mime_type)) {
      if($class_instance = $this->getFromatterClassInstance($mime_type)) {
        return $class_instance;
      }
    }

    throw new \InvalidArgumentException('The requested response format is not supported!');
  }


  private function getFromatterClassInstance($mime_type) {
    if($subtype = $this->getSubtype($mime_type)) {
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
