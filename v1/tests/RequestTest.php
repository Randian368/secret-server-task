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

}
