<?php
namespace Base;

interface ResponseFormatterInterface {
  public function format(&$response);

  public function getFormatMimeType();
}
