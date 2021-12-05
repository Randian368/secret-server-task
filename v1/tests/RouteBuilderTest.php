<?php
declare(strict_types = 1);

use PHPUnit\Framework\TestCase;

final class RouteBuilderTest extends TestCase {

  /**
  * @test
  */
  public function testBuild(): void {
    $path = 'security/encryption/hash_method'; // no such controller

    $route_builder = new \Builder\RouteBuilder();
    $route = $route_builder->build($path);
    $this->assertInstanceOf('\\Route', $route);

    $path = 'secret';
    $route = $route_builder->build($path);
    $this->assertNotNull($route);
    $this->assertInstanceOf('\\Route', $route);
    $this->assertSame($route->getMethod(), 'post');
    $this->assertSame($route->getArgument(), '');

    $path = 'secret/this_is_a_hash';
    $route = $route_builder->build($path);
    $this->assertNotNull($route);
    $this->assertInstanceOf('\\Route', $route);
    $this->assertSame($route->getMethod(), 'get');
    $this->assertSame($route->getArgument(), 'this_is_a_hash');
  }


}
