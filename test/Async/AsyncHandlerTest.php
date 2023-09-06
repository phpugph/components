<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Async;

use PHPUnit\Framework\TestCase;

class AsyncHandlerTest extends TestCase
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
   * @covers UGComponents\Async\AsyncHandler::__construct
   * @covers UGComponents\Async\AsyncHandler::getChannelMap
   */
  public function test__construct()
  {
    $handler = new AsyncHandler();
    $this->assertInstanceOf('UGComponents\Async\AsyncHandler', $handler);

    $handler = new AsyncHandler(function() {});
    $this->assertInstanceOf('UGComponents\Async\AsyncHandler', $handler);

    $handler = new AsyncHandler('noop');
    $this->assertInstanceOf('UGComponents\Async\AsyncHandler', $handler);

    $handler = new AsyncHandler('foo');
    $this->assertInstanceOf('UGComponents\Async\AsyncHandler', $handler);
  }

  /**
   * @covers UGComponents\Async\AsyncHandler::__construct
   * @covers UGComponents\Async\AsyncHandler::getChannelMap
   * @covers UGComponents\Async\AsyncHandler::add
   * @covers UGComponents\Async\AsyncHandler::run
   */
  public function testRun()
  {
    $handler = new AsyncHandler('noop');
    $results = [];

    $routine1 = $handler->add(function($routine) use (&$results) {
      for($i = 0; $i < 5; $i++) {
        $results[] = $routine->getId() . '-' . $i;
        yield;
      }
    });

    $routine2 = $handler->add(function($routine) use (&$results) {
      for($i = 0; $i < 3; $i++) {
        $results[] = $routine->getId() . '-' . $i;
        yield;
      }
    });

    $handler->run();

    $this->assertEquals($routine1->getId() . '-0', $results[0]);
    $this->assertEquals($routine2->getId() . '-0', $results[1]);
    $this->assertEquals($routine1->getId() . '-1', $results[2]);
    $this->assertEquals($routine2->getId() . '-1', $results[3]);
    $this->assertEquals($routine1->getId() . '-2', $results[4]);
    $this->assertEquals($routine2->getId() . '-2', $results[5]);
    $this->assertEquals($routine1->getId() . '-3', $results[6]);
    $this->assertEquals($routine1->getId() . '-4', $results[7]);

    $handler = new AsyncHandler('noop');
    $results = [];

    $routine1 = $handler->add(function($routine) {
      for($i = 0; $i < 5; $i++) {
        yield $routine->getId() . '-' . $i;
      }
    });

    $routine2 = $handler->add(function($routine) {
      for($i = 0; $i < 3; $i++) {
        yield $routine->getId() . '-' . $i;
      }
    });

    $handler->run(function($value, $routine) use (&$results) {
      $results[] = $value;
    });

    $this->assertEquals($routine1->getId() . '-0', $results[0]);
    $this->assertEquals($routine2->getId() . '-0', $results[1]);
    $this->assertEquals($routine1->getId() . '-1', $results[2]);
    $this->assertEquals($routine2->getId() . '-1', $results[3]);
    $this->assertEquals($routine1->getId() . '-2', $results[4]);
    $this->assertEquals($routine2->getId() . '-2', $results[5]);
    $this->assertEquals($routine1->getId() . '-3', $results[6]);
    $this->assertEquals($routine1->getId() . '-4', $results[7]);
  }

  /**
   * @covers UGComponents\Async\AsyncHandler::add
   * @covers UGComponents\Async\AsyncHandler::kill
   * @covers UGComponents\Async\AsyncHandler::__construct
   * @covers UGComponents\Async\AsyncHandler::getChannelMap
   * @covers UGComponents\Async\AsyncHandler::run
   */
  public function testKill()
  {
    $handler = new AsyncHandler('noop');

    $count = 0;

    $routine1 = $handler->add(function($routine1) {
      for($i = 0; $i < 5; $i++) {
        yield $i;
      }
    });

    $routine2 = $handler->add(function($routine2) {
      sleep();
      for($i = 0; $i < 5; $i++) {
        yield $i;
      }
    });

    $count = 0;
    $handler->run(function($value) use (&$handler, &$count, &$routine2, &$routine1) {
      $count += $value;
      $handler->kill($routine2->getId());
    });

    $this->assertEquals(10, $count);
  }
}
