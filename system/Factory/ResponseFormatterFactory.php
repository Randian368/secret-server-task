<?php
namespace ResponseFormatter;

class ResponseFormatterFactory {


  public static function createFormatter($accept_mime_type) {
    if($subtype = $this->getSubtype($accept_mime_type)) {
      $class_name = 'ResponseFormatter/' . $this->getFormatterClassName($subtype);
      if(class_exists($class_name)) {
        return new $class_name();
      }
    }

    throw new InvalidArgumentException('The following content type is not supported: ' . $accept_mime_type);
  }


  private function getSubtype($accept_mime_type) {
    if(preg_match('/[^;\r\n\s]+(?:\\|\/)([^;\r\n\s]+)(?:$|[;\r\n\s])/', $accept_mime_type, $subtype) && isset($subtype[1])) {
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
        $formatter_class_name .= ucfirst(substr($subtype, $start, $end));
      }

    } else {
      $formatter_class_name = ucfirst($subtype);
    }

    $formatter_class_name .= 'ResponseFormatter';
    return $formatter_class_name;
  }

}
