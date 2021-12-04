<?php
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;

final class RouteBuilderTest extends TestCase {

  /**
  * @test
  */
  public function testBuild(): void {
    $path = 'security/encryption/hash_method/string_parameter'; // no such controller

    $route_builder = new \Builder\RouteBuilder();
    $route_builder->setRequestMethod('GET');

    $route = $route_builder->build($path);
    $this->assertSame($route, null);

    $path = 'secret';
    $route_builder->setRequestMethod('POST');

    $route = $route_builder->build($path);
    $this->assertNotNull($route);
    $this->assertInstanceOf('\\Route', $route);
    $this->assertSame($route->getMethod(), 'createNewSecret');
    $this->assertSame($route->getArgument(), '');

    $path = 'secret/this_is_a_hash';
    $route_builder->setRequestMethod('GET');

    $route = $route_builder->build($path);
    $this->assertNotNull($route);
    $this->assertInstanceOf('\\Route', $route);
    $this->assertSame($route->getMethod(), 'getSecretByHash');
    $this->assertSame($route->getArgument(), 'this_is_a_hash');
  }


}
