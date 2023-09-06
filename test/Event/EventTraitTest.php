<?php

namespace UGComponents\Event;

use StdClass;
use PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 02:11:00.
 */
class EventTraitTest extends TestCase
{
  /**
   * @var EventTrait
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp(): void
  {
    $this->object = new EventTraitStub;
    $this->object->getEventEmitter()->unbind();
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown(): void
  {
  }

  /**
   * @covers UGComponents\Event\EventTrait::async
   */
  public function testAsync()
  {
    $trigger = new StdClass();
    $trigger->success = null;
    $trigger->results = 0;

    $process = function() use ($trigger) {
      $trigger->success = true;
      $trigger->results += 5;
    };

    $process2 = function() use ($trigger) {
      $trigger->results += 10;
    };

    $instance = $this
      ->object
      ->on('foo', $process)
      ->on('bar', $process2)
      ->async('foo')
      ->async('bar');

    $this->assertInstanceOf('UGComponents\Event\EventTraitStub', $instance);

    $instance = $instance->getAsyncHandler();
    $this->assertInstanceOf('UGComponents\Async\AsyncHandler', $instance);

    $instance->run();
    $this->assertTrue($trigger->success);
    $this->assertEquals(15, $trigger->results);
  }

  /**
   * @covers UGComponents\Event\EventTrait::getEventEmitter
   */
  public function testGetEventEmitter()
  {
    $instance = $this->object->getEventEmitter();
    $this->assertInstanceOf('UGComponents\Event\EventEmitter', $instance);

    $instance = $this->object
      ->setEventEmitter(new EventTraitEventEmitterStub)
      ->getEventEmitter();
    $this->assertInstanceOf('UGComponents\Event\EventTraitEventEmitterStub', $instance);
  }

  /**
   * @covers UGComponents\Event\EventTrait::on
   */
  public function testOn()
  {
    $trigger = new StdClass();
    $trigger->success = null;

    $callback = function() use ($trigger) {
      $trigger->success = true;
    };

    $instance = $this
      ->object
      ->on('foobar', $callback)
      ->emit('foobar');

    $this->assertInstanceOf('UGComponents\Event\EventTraitStub', $instance);
    $this->assertTrue($trigger->success);
  }

  /**
   * @covers UGComponents\Event\EventTrait::setEventEmitter
   */
  public function testSetEventEmitter()
  {
    $instance = $this->object->setEventEmitter(new EventTraitEventEmitterStub);
    $this->assertInstanceOf('UGComponents\Event\EventTraitStub', $instance);

    $instance = $this->object->setEventEmitter(new EventTraitEventEmitterStub, true);
    $this->assertInstanceOf('UGComponents\Event\EventTraitStub', $instance);

    $instance = $this->object->setEventEmitter(new EventEmitter, true);
  }

  /**
   * @covers UGComponents\Event\EventTrait::emit
   */
  public function testEmit()
  {
    $trigger = new StdClass();
    $trigger->success = null;

    $callback = function() use ($trigger) {
      $trigger->success = true;
    };

    $instance = $this
      ->object
      ->on('foobar', $callback)
      ->emit('foobar');

    $this->assertInstanceOf('UGComponents\Event\EventTraitStub', $instance);
    $this->assertTrue($trigger->success);
  }

  /**
   * @covers UGComponents\Event\EventTrait::bindCallback
   */
  public function testBindCallback()
  {
    $trigger = new StdClass;
    $trigger->success = null;
    $trigger->test = $this;

    $callback = $this->object->bindCallback(function() use ($trigger) {
      $trigger->success = true;
      $trigger->test->assertInstanceOf('UGComponents\Event\EventTraitStub', $this);
    });

    $callback();

    $this->assertTrue($trigger->success);
  }
}

if(!class_exists('UGComponents\Event\EventTraitStub')) {
  class EventTraitStub
  {
    use EventTrait;
  }
}

if(!class_exists('UGComponents\Event\EventTraitEventEmitterStub')) {
  class EventTraitEventEmitterStub extends EventEmitter
  {
  }
}