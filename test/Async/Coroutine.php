<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Async;

use PHPUnit\Framework\TestCase;

class Async_Coroutine_Test extends TestCase
{
  /**
   * @var
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp(): void
  {
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown(): void
  {
  }

  /**
   * @covers UGComponents\Async\Coroutine::__construct
   */
  public function test__construct()
  {
    $message = null;
    try {
      new Coroutine('foo');
    } catch(AsyncException $e) {
      $message = $e->getMessage();
    }

    $this->assertEquals('Argument 1 was expecting either a Generator or callable, string used.', $message);

  }

  /**
   * @covers UGComponents\Async\Coroutine::getId
   * @covers UGComponents\Async\Coroutine::__construct
   */
  public function testGetId()
  {
    $noop = function() {};
    $coroutine = new Coroutine($noop);
    $this->assertEquals(spl_object_hash($noop), $coroutine->getId());

    $coroutine = new Coroutine('getcwd');
    $this->assertEquals('getcwd', $coroutine->getId());

    $routine = new RoutineStub();
    $coroutine = new Coroutine([$routine, 'foo']);
    $this->assertEquals(spl_object_hash($routine) . '::foo', $coroutine->getId());

    $coroutine = new Coroutine(['UGComponents\Async\RoutineStub', 'bar']);
    $this->assertEquals('UGComponents\Async\RoutineStub::bar', $coroutine->getId());
  }

  /**
   * @covers UGComponents\Async\Coroutine::getValue
   * @covers UGComponents\Async\Coroutine::run
   * @covers UGComponents\Async\Coroutine::makeRoutine
   * @covers UGComponents\Async\Coroutine::step
   */
  public function testGetValue()
  {
    $coroutine = new Coroutine(function() {
      return 'foo';
    });

    $coroutine->run();
    $this->assertEquals('foo', $coroutine->getValue());
  }

  /**
   * @covers UGComponents\Async\Coroutine::isFinished
   * @covers UGComponents\Async\Coroutine::run
   * @covers UGComponents\Async\Coroutine::makeRoutine
   * @covers UGComponents\Async\Coroutine::step
   */
  public function testIsFinished()
  {
    $coroutine = new Coroutine(function() {
      yield 'foo';
      yield 'bar';
    });

    $coroutine->run();
    $this->assertFalse($coroutine->isFinished());

    $coroutine->run();
    $coroutine->run();
    $this->assertTrue($coroutine->isFinished());
  }

  /**
   * @covers UGComponents\Async\Coroutine::isStarted
   * @covers UGComponents\Async\Coroutine::run
   * @covers UGComponents\Async\Coroutine::makeRoutine
   * @covers UGComponents\Async\Coroutine::step
   */
  public function testIsStarted()
  {
    $coroutine = new Coroutine(function() {
      yield 'foo';
    });

    $this->assertFalse($coroutine->isStarted());

    $coroutine->run();
    $this->assertTrue($coroutine->isStarted());
  }

  /**
   * @covers UGComponents\Async\Coroutine::reset
   * @covers UGComponents\Async\Coroutine::run
   * @covers UGComponents\Async\Coroutine::makeRoutine
   * @covers UGComponents\Async\Coroutine::step
   */
  public function testReset()
  {
    $coroutine = new Coroutine(function() {
      yield 'foo';
      yield 'bar';
    });

    $coroutine->run();
    $this->assertEquals('foo', $coroutine->getValue());

    $coroutine->run();
    $this->assertEquals('bar', $coroutine->getValue());

    $coroutine->reset();
    $coroutine->run();
    $this->assertEquals('foo', $coroutine->getValue());
  }
}

class RoutineStub
{
  public function foo()
  {
    return 'bar';
  }

  public static function bar()
  {
    return 'foo';
  }
}
