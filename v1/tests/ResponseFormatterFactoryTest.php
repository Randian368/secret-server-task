<?php
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;

final class ResponseFormatterFactoryTest extends TestCase {
    /**
   * @test
   */
  public function testGetFormatterClassName(): void {
    $reflection = new ReflectionClass(new \Factory\ResponseFormatterFactory());

    $reflection_method = $reflection->getMethod('getFormatterClassName');
    $reflection_method->setAccessible(true);

    $subtype = 'vnd.ms-powerpoint';
    $formatter_class_name = $reflection_method->invoke(new \Factory\ResponseFormatterFactory(), $subtype);
    $this->assertSame($formatter_class_name, 'ResponseFormatter\VndMsPowerpointResponseFormatter');

    $subtype = 'xml';
    $formatter_class_name = $reflection_method->invoke(new \Factory\ResponseFormatterFactory(), $subtype);
    $this->assertSame($formatter_class_name, 'ResponseFormatter\XmlResponseFormatter');

    $subtype = 'json';
    $formatter_class_name = $reflection_method->invoke(new \Factory\ResponseFormatterFactory(), $subtype);
    $this->assertSame($formatter_class_name, 'ResponseFormatter\JsonResponseFormatter');
  }
}
