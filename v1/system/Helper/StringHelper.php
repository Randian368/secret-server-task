<?php
namespace Helper;

class StringHelper {
  public static function format_as_class_name(String $text) {
    $class_name = '';

    preg_match_all('/[^\p{L}\d]/', $text, $word_delimiters, PREG_OFFSET_CAPTURE);

    if(!empty($word_delimiters[0])) {
      for($i = 0; $i <= count($word_delimiters[0]); $i++) {
        $delimiter = isset($word_delimiters[0][$i]) ? $word_delimiters[0][$i] : ['', strlen($text)];

        $start = isset($word_delimiters[0][$i - 1]) ? ($word_delimiters[0][$i - 1][1] + 1) : 0;
        $end = $delimiter[1] - $start;

        $class_name .= StringHelper::ucfirstLcrest(substr($text, $start, $end));
      }
    } else {
      $class_name = StringHelper::ucfirstLcrest($text);
    }

    return $class_name;
  }


  public static function ucfirstLcrest($string) {
    return strtoupper(substr($string, 0, 1)) . strtolower(substr($string, 1));
  }
}
