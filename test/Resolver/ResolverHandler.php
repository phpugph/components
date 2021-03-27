<?php

namespace UGComponents\Resolver;

use PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 02:11:02.
 */
class Resolver_ResolverHandler_Test extends TestCase
{
  /**
   * @var ResolverHandler
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp(): void
  {
    $this->object = new ResolverHandler;
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown(): void
  {
  }

  /**
   * @covers UGComponents\Resolver\ResolverHandler::canResolve
   */
  public function testCanResolve()
  {
    $actual = $this->object->canResolve('array_pop');
    $this->assertTrue($actual);

    $actual = $this->object->canResolve(ResolverHandlerCanStub::class . '::itCanResolve');
    $this->assertTrue($actual);

    $actual = $this->object->register('foobar', function() {})->canResolve('foobar');
    $this->assertTrue($actual);

    $actual = $this->object->canResolve('barfoo');
    $this->assertFalse($actual);
  }

  /**
   * @covers UGComponents\Resolver\ResolverHandler::isRegistered
   */
  public function testIsRegistered()
  {
    $actual = $this->object->register('foobar', function() {})->isRegistered('foobar');
    $this->assertTrue($actual);

    $actual = $this->object->isRegistered('barfoo');
    $this->assertFalse($actual);
  }

  /**
   * @covers UGComponents\Resolver\ResolverHandler::isShared
   */
  public function testIsShared()
  {
    $this->object->register('foobar', function() { return 1; })->shared('foobar');
    $this->object->shared('foobar');
    $actual = $this->object->isShared('foobar');
    $this->assertTrue($actual);

    $actual = $this->object->isShared('barfoo');
    $this->assertFalse($actual);
  }

  /**
   * @covers UGComponents\Resolver\ResolverHandler::register
   */
  public function testRegister()
  {
    $actual = $this
      ->object
      ->register('foobar', function() {
        return 1;
      })
      ->resolve('foobar');

    $this->assertEquals(1, $actual);
  }

  /**
   * @covers UGComponents\Resolver\ResolverHandler::resolve
   */
  public function testResolve()
  {
    $actual = $this->object->register(
      ResolverCallStub::class,
      function() {
        return new ResolverAddStub();
      }
    )
    ->resolve(ResolverCallStub::class)
    ->foo('bar');

    $this->assertEquals('barfoo', $actual);

    $trigger = false;

    try {
      $this->object->resolve('barfoo');
    } catch(ResolverException $e) {
      $trigger = true;
    }

    $this->assertTrue($trigger);

    $this->assertEquals('0', $this->object->resolve('strpos', 'Foobar', 'Foo'));
  }

  /**
   * @covers UGComponents\Resolver\ResolverHandler::resolveStatic
   */
  public function testResolveStatic()
  {
    $actual = $this
      ->object
      ->resolveStatic(
        ResolverStaticStub::class,
        'foo',
        'bar'
      );

    $this->assertEquals('barfoo', $actual);
  }

  /**
   * @covers UGComponents\Resolver\ResolverHandler::shared
   */
  public function testShared()
  {
     $actual = $this
      ->object
      ->shared(ResolverSharedStub::class)
      ->reset()
      ->foo('bar');

    $this->assertEquals('barfoo', $actual);

    $actual = $this
      ->object
      ->shared(ResolverSharedStub::class)
      ->foo('bar');

    $this->assertEquals('barbar', $actual);
  }
}

if(!class_exists('UGComponents\Resolver\ResolverHandlerCanStub')) {
  class ResolverHandlerCanStub
  {
    public static function itCanResolve()
    {
      return true;
    }
  }
}

if(!class_exists('UGComponents\Resolver\ResolverCallStub')) {
  class ResolverCallStub
  {
    public function foo($string)
    {
      return $string . 'foo';
    }
  }
}

if(!class_exists('UGComponents\Resolver\ResolverSharedStub')) {
  class ResolverSharedStub
  {
    public $name = 'foo';

    public function foo($string)
    {
      $name = $this->name;
      $this->name = $string;
      return $string . $name;
    }

    public function reset()
    {
      $this->name = 'foo';
      return $this;
    }
  }
}

if(!class_exists('UGComponents\Resolver\ResolverStaticStub')) {
  class ResolverStaticStub
  {
    public static function foo($string)
    {
      return $string . 'foo';
    }
  }
}
