<?php
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;

final class RequestTest extends TestCase {

  /**
  * @test
  */
  public function testIsValidRequestRoute() {
    $route = new \Route();
    $request = new \Request();

    $this->assertFalse($request->isValidRequestRoute($route));

    $route->setClass(new \Controller\SecretServer());
    $route->setMethod('post');
    $this->assertTrue($request->isValidRequestRoute($route));

    $route->setMethod('get');
    $route->setArgument('test_hash');
    $this->assertTrue($request->isValidRequestRoute($route));
  }


  /**
  * @test
  */
  public function testGetAcceptMimeType() {
    $request = new \Request();
    $request->setHttpHeaders(['Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9']);
    $this->assertSame($request->getAcceptMimeType(), explode(',', 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9'));

    $request = new \Request();
    $request->setHttpHeaders(['User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0']);
    $this->assertSame($request->getAcceptMimeType(), 'application/json');
  }


  /**
  * @test
  */
  public function testIsSupportedProtocol() {
    $request = new \Request();
    $request->setProtocol('SOAP');
    $this->assertFalse($request->isSupportedProtocol());

    $request->setProtocol('HTTP/1.1');
    $this->assertTrue($request->isSupportedProtocol());
  }


  /**
  * @test
  */
  public function testIsSupportedHttpMethod() {
    $request = new \Request();
    $request->setHttpMethod('PUT');
    $this->assertFalse($request->isSupportedHttpMethod());

    $request->setHttpMethod('POST');
    $this->assertTrue($request->isSupportedHttpMethod());
  }


  public function testGetResponse() {
    $route = new \Route();

  }

}
