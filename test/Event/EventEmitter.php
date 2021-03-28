<?php

namespace UGComponents\Event;

use StdClass;
use PHPUnit\Framework\TestCase;
use UGComponents\Resolver\ResolverHandler;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 02:11:00.
 */
class Event_EventEmitter_Test extends TestCase
{
  /**
   * @var EventEmitter
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp(): void
  {
    $this->object = new EventEmitter;
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown(): void
  {
    $this->object->unbind('foobar');
  }

  /**
   * @covers UGComponents\Event\EventEmitter::unbind
   * @covers UGComponents\Event\EventEmitter::removeObserversByEvent
   * @covers UGComponents\Event\EventEmitter::removeObserversByCallback
   */
  public function testOff()
  {
    $trigger = new StdClass();
    $trigger->success = null;

    $callback = function() use ($trigger) {
      $trigger->success = true;
    };

    $this
      ->object
      ->on('foobar', $callback)
      ->unbind('foobar')
      ->emit('foobar');

    $this->assertNull($trigger->success);

    $this->object->unbind();

    $this->assertNull($trigger->success);

    $this
      ->object
      ->on('foobar', $callback)
      ->unbind(null, $callback)
      ->emit('foobar');

    $this->assertNull($trigger->success);

    $this
      ->object
      ->on('foobar', $callback)
      ->unbind('foobar', $callback)
      ->emit('foobar');

    $this->assertNull($trigger->success);
  }

  /**
   * @covers UGComponents\Event\EventEmitter::on
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
      ->on(['foobar', 'bar %s foo'], $callback)
      ->emit('foobar');

    $this->assertInstanceOf('UGComponents\Event\EventEmitter', $instance);
    $this->assertTrue($trigger->success);

    $trigger->success = null;
    $instance = $this->object->emit('bar zoo foo');

    $this->assertInstanceOf('UGComponents\Event\EventEmitter', $instance);
    $this->assertTrue($trigger->success);

    $trigger = new StdClass();
    $trigger->success1 = null;
    $trigger->success2 = null;

    $this->object
      ->on('barzoo', function($trigger) {
        $trigger->success1 = true;
        return false;
      })
      ->on('barzoo', function($trigger) {
        $trigger->success2 = true;
      })
      ->emit('barzoo', $trigger);

    $this->assertTrue($trigger->success1);
    $this->assertNull($trigger->success2);

    $this->assertEquals(308, $this->object->getMeta());
  }

  /**
   * @covers UGComponents\Event\EventEmitter::emit
   */
  public function testTrigger()
  {
    $trigger = new StdClass();
    $trigger->success = null;
    $trigger->test = $this;

    $callback = function($foo, $bar) use ($trigger) {
      $trigger->success = true;
      $trigger->test->assertEquals(1, $foo);
      $trigger->test->assertEquals(2, $bar);

      return false;
    };

    $callback2 = function($foo, $bar) use ($trigger) {
      $trigger->success = false;
    };

    $meta = $this
      ->object
      ->emit('foobar', 1, 2)
      ->getMeta();

    $this->assertEquals(404, $meta);

    $meta = $this
      ->object
      ->on('foobar', $callback2)
      ->emit('foobar', 1, 2)
      ->getMeta();

    $this->assertEquals(200, $meta);

    $instance = $this
      ->object
      ->on('foobar2', $callback)
      ->on('foobar2', $callback2)
      ->emit('foobar2', 1, 2);

    $this->assertInstanceOf('UGComponents\Event\EventEmitter', $instance);
    $this->assertTrue($trigger->success);
    $this->assertEquals(308, $this->object->getMeta());
  }

  /**
   * @covers UGComponents\Event\EventEmitter::__callResolver
   */
  public function test__callResolver()
  {
    $actual = $this->object->__callResolver(ResolverCallStub::class, [])->foo('bar');
    $this->assertEquals('barfoo', $actual);
  }

  /**
   * @covers UGComponents\Event\EventEmitter::addResolver
   */
  public function testAddResolver()
  {
    $actual = $this->object->addResolver(ResolverCallStub::class, function() {});
    $this->assertInstanceOf('UGComponents\Event\EventEmitter', $actual);
  }

  /**
   * @covers UGComponents\Event\EventEmitter::getResolverHandler
   */
  public function testGetResolverHandler()
  {
    $actual = $this->object->getResolverHandler();
    $this->assertInstanceOf('UGComponents\Resolver\ResolverInterface', $actual);
  }

  /**
   * @covers UGComponents\Event\EventEmitter::resolve
   */
  public function testResolve()
  {
    $actual = $this->object->addResolver(
      ResolverCallStub::class,
      function() {
        return new ResolverAddStub();
      }
    )
    ->resolve(ResolverCallStub::class)
    ->foo('bar');

    $this->assertEquals('barfoo', $actual);
  }

  /**
   * @covers UGComponents\Event\EventEmitter::resolveShared
   */
  public function testResolveShared()
  {
    $actual = $this
      ->object
      ->resolveShared(ResolverSharedStub::class)
      ->reset()
      ->foo('bar');

    $this->assertEquals('barfoo', $actual);

    $actual = $this
      ->object
      ->resolveShared(ResolverSharedStub::class)
      ->foo('bar');

    $this->assertEquals('barbar', $actual);
  }

  /**
   * @covers UGComponents\Event\EventEmitter::resolveStatic
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
   * @covers UGComponents\Event\EventEmitter::setResolverHandler
   */
  public function testSetResolverHandler()
  {
    $actual = $this->object->setResolverHandler(new ResolverHandlerStub);
    $this->assertInstanceOf('UGComponents\Event\EventEmitter', $actual);
  }

  /**
   * @covers UGComponents\Event\EventEmitter::match
   */
  public function testMatch()
  {
    $trigger = new StdClass();
    $trigger->success1 = null;

    $this->object->on('#foo.*bar#is', function($trigger) {
      $trigger->success1 = true;
    })
    ->emit('foo zoo bar', $trigger);

    $this->assertTrue($trigger->success1);

    $trigger->success2 = null;

    $this->object->on('#(?=.*create)(?=.*address)(?=.*sql)#is', function($trigger) {
      $trigger->success2 = true;
    })
    ->emit('Create SQL Address', $trigger);

    $this->assertTrue($trigger->success2);

    $trigger->success3 = null;

    $this->object->on('Create %s Address', function($trigger) {
      $trigger->success3 = true;
    })
    ->emit('Create SQL Address', $trigger);

    $this->assertTrue($trigger->success3);

    $trigger->success4 = null;

    $this->object->on('Create %s', function($trigger) {
      $trigger->success4 = true;
    })
    ->emit('SQL Address', $trigger);

    $this->assertNull($trigger->success4);

    $this->object->on('Create', function($trigger) {
      $trigger->success4 = true;
    })
    ->emit('Create', $trigger);

    $this->assertTrue($trigger->success4);
  }

  /**
   * @covers UGComponents\Event\EventEmitter::getMeta
   */
  public function testGetMeta()
  {
    $trigger = new StdClass();
    $trigger->success1 = null;
    $trigger->success2 = null;
    $trigger->success3 = null;
    $trigger->success4 = null;
    $trigger->success5 = null;

    $this
      ->object
      ->on('foo zoo bar', function($trigger, $handler, $test) {
        $trigger->success1 = true;

        $event = $handler->getMeta();

        $test->assertEquals('foo zoo bar', $event['event']);
        $test->assertEquals('foo zoo bar', $event['pattern']);
        $test->assertTrue(empty($event['variables']));
        $this->assertCount(3, $event['args']);
      })
      ->on('foo %s bar', function($trigger, $handler, $test) {
        $trigger->success2 = true;

        $event = $handler->getMeta();

        $test->assertEquals('foo zoo bar', $event['event']);
        $test->assertEquals('#foo (.+) bar#s', $event['pattern']);
        $test->assertEquals('zoo', $event['variables'][0]);
        $this->assertCount(3, $event['args']);
      })
      ->on('#foo\s*(.*)\s+bar#is', function($trigger, $handler, $test) {
        $trigger->success3 = true;

        $event = $handler->getMeta();

        $test->assertEquals('foo zoo bar', $event['event']);
        $test->assertEquals('#foo\s*(.*)\s+bar#is', $event['pattern']);
        $test->assertEquals('zoo', $event['variables'][0]);
        $this->assertCount(3, $event['args']);
      })
      ->on('#foo\s*(.*)\s+bar#is', function($trigger, $handler, $test) {
        $trigger->success4 = true;

        return false;
      })
      ->on('#foo\s*(.*)\s+bar#is', function($trigger, $handler, $test) {
        $trigger->success5 = true;
      })

      ->emit('foo zoo bar', $trigger, $this->object, $this);

    $this->assertTrue($trigger->success1);
    $this->assertTrue($trigger->success2);
    $this->assertTrue($trigger->success3);
    $this->assertTrue($trigger->success4);
    $this->assertNull($trigger->success5);

  }
}

if(!class_exists('UGComponents\Event\ResolverCallStub')) {
  class ResolverCallStub
  {
    public function foo($string)
    {
      return $string . 'foo';
    }
  }
}

if(!class_exists('UGComponents\Event\ResolverAddStub')) {
  class ResolverAddStub
  {
    public function foo($string)
    {
      return $string . 'foo';
    }
  }
}

if(!class_exists('UGComponents\Event\ResolverSharedStub')) {
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

if(!class_exists('UGComponents\Event\ResolverStaticStub')) {
  class ResolverStaticStub
  {
    public static function foo($string)
    {
      return $string . 'foo';
    }
  }
}

if(!class_exists('UGComponents\Event\ResolverHandlerStub')) {
  class ResolverHandlerStub extends ResolverHandler
  {
  }
}
