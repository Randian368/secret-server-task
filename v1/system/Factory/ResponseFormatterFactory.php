<?php
namespace ResponseFormatter;

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

    preg_match_all('/[^\p{L}]/', $subtype, $word_delimiters, PREG_OFFSET_CAPTURE);

    if(!empty($word_delimiters[0])) {
      for($i = 0; $i <= count($word_delimiters[0]); $i++) {
        $delimiter = isset($word_delimiters[0][$i]) ? $word_delimiters[0][$i] : ['', strlen($subtype)];

        $start = isset($word_delimiters[0][$i - 1]) ? ($word_delimiters[0][$i - 1][1] + 1) : 0;
        $end = $delimiter[1] - $start;

        $type_name .= ucfirst(substr($subtype, $start, $end));
      }
    } else {
      $type_name = ucfirst($subtype);
    }

    if($type_name) {
      $formatter_class_name = 'ResponseFormatter\\' . $type_name . 'ResponseFormatter';
      return $formatter_class_name;
    }
  }
}
