<?php

namespace UGComponents\Data;

use StdClass;
use PHPUnit\Framework\TestCase;
use UGComponents\Data\Collection\CollectionException;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 02:10:59.
 */
class Data_Collection_Test extends TestCase
{
  /**
   * @var Collection
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   *
   * @covers UGComponents\Data\Collection::__construct
   */
  protected function setUp(): void
  {
    $this->object = new Collection([
      [
        'post_id' => 1,
        'post_title' => 'Foobar 1',
        'post_detail' => 'foobar 1',
        'post_active' => 1
      ],
      [
        'post_id' => 2,
        'post_title' => 'Foobar 2',
        'post_detail' => 'foobar 2',
        'post_active' => 0
      ],
      [
        'post_id' => 3,
        'post_title' => 'Foobar 3',
        'post_detail' => 'foobar 3',
        'post_active' => 1
      ]
    ]);
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown(): void
  {
  }

  /**
   * @covers UGComponents\Data\Collection::__construct
   */
  public function test__construct()
  {
    $actual = $this->object->__construct([
      [
        'post_id' => 1,
        'post_title' => 'Foobar 1',
        'post_detail' => 'foobar 1',
        'post_active' => 1
      ],
      [
        'post_id' => 2,
        'post_title' => 'Foobar 2',
        'post_detail' => 'foobar 2',
        'post_active' => 0
      ],
      [
        'post_id' => 3,
        'post_title' => 'Foobar 3',
        'post_detail' => 'foobar 3',
        'post_active' => 1
      ]
    ]);

    $this->assertNull($actual);
  }

  /**
   * @covers UGComponents\Data\Collection::__call
   */
  public function test__call()
  {
    $instance = $this->object->__call('setPostTitle', array('Foobar 4'));
    $this->assertInstanceOf('UGComponents\Data\Collection', $instance);

    $actual = $this->object->__call('getPostTitle', array());

    foreach($actual as $title) {
      $this->assertEquals('Foobar 4', $title);
    }

    $instance = $this->object->__call('isDot', array('post_title'));
    $this->assertInstanceOf('UGComponents\Data\Collection', $instance);

    $instance = $this->object->__call(Model::class, array());
    $this->assertInstanceOf('UGComponents\Data\Model', $instance);

    $thrown = false;
    try {
      $this->object->__call('foobar', array());
    } catch(CollectionException $e) {
      $thrown = true;
    }

    $this->assertTrue($thrown);
  }

  /**
   * @covers UGComponents\Data\Collection::__get
   */
  public function test__get()
  {
    $actual = $this->object->post_title;

    foreach($actual as $title) {
      $this->assertStringContainsString('Foobar', $title);
    }
  }

  /**
   * @covers UGComponents\Data\Collection::__set
   */
  public function test__set()
  {
    $this->object->post_title = 'Foobar 4';
    $actual = $this->object->post_title;

    foreach($actual as $title) {
      $this->assertEquals('Foobar 4', $title);
    }
  }

  /**
   * @covers UGComponents\Data\Collection::__toString
   */
  public function test__toString()
  {
    $this->assertEquals(json_encode([
      [
        'post_id' => 1,
        'post_title' => 'Foobar 1',
        'post_detail' => 'foobar 1',
        'post_active' => 1
      ],
      [
        'post_id' => 2,
        'post_title' => 'Foobar 2',
        'post_detail' => 'foobar 2',
        'post_active' => 0
      ],
      [
        'post_id' => 3,
        'post_title' => 'Foobar 3',
        'post_detail' => 'foobar 3',
        'post_active' => 1
      ]
    ], JSON_PRETTY_PRINT), (string) $this->object);
  }

  /**
   * @covers UGComponents\Data\Collection::add
   */
  public function testAdd()
  {
    $instance = $this->object->add([
      'post_id' => 4,
      'post_title' => 'Foobar 4',
      'post_detail' => 'foobar 4',
      'post_active' => 0
    ]);

    $this->assertInstanceOf('UGComponents\Data\Collection', $instance);
  }

  /**
   * @covers UGComponents\Data\Collection::cut
   */
  public function testCut()
  {
    $instance = $this->object->cut(1);

    $this->assertInstanceOf('UGComponents\Data\Collection', $instance);
    $this->assertEquals(2, count($instance));

    $this->setUp();

    $instance = $this->object->cut('first');

    $this->assertInstanceOf('UGComponents\Data\Collection', $instance);
    $this->assertEquals(2, count($instance));

    $this->setUp();

    $instance = $this->object->cut('last');

    $this->assertInstanceOf('UGComponents\Data\Collection', $instance);
    $this->assertEquals(2, count($instance));
  }

  /**
   * @covers UGComponents\Data\Collection::each
   * @todo   Implement testEach().
   */
  public function testEach()
  {
    $trigger = new StdClass();
    $trigger->total = 0;
    $instance = $this->object->each(function($i, $row) use ($trigger) {
      $trigger->total++;
    });

    $this->assertInstanceOf('UGComponents\Data\Collection', $instance);
    $this->assertEquals(3, $trigger->total);
  }

  /**
   * @covers UGComponents\Data\Collection::get
   * @todo   Implement testGet().
   */
  public function testGet()
  {
    $data = $this->object->get();

    $this->assertTrue(is_array($data));
    $this->assertTrue(is_array($data[2]));
  }

  /**
   * @covers UGComponents\Data\Collection::getModel
   * @todo   Implement testGetModel().
   */
  public function testGetModel()
  {
    $instance = $this->object->getModel([
      'post_id' => 4,
      'post_title' => 'Foobar 4',
      'post_detail' => 'foobar 4',
      'post_active' => 0
    ]);

    $this->assertInstanceOf('UGComponents\Data\Model', $instance);
  }

  /**
   * @covers UGComponents\Data\Collection::set
   * @todo   Implement testSet().
   */
  public function testSet()
  {
    $instance = $this->object->set([
      [
        'post_id' => 4,
        'post_title' => 'Foobar 4',
        'post_detail' => 'foobar 4',
        'post_active' => 0
      ]
    ]);

    $this->assertInstanceOf('UGComponents\Data\Collection', $instance);
    $this->assertEquals(4, count($instance));
  }

  /**
   * @covers UGComponents\Data\Collection::offsetExists
   */
  public function testOffsetExists()
  {
    $this->assertTrue($this->object->offsetExists(0));
    $this->assertFalse($this->object->offsetExists(3));
  }

  /**
   * @covers UGComponents\Data\Collection::offsetGet
   */
  public function testOffsetGet()
  {
    $instance = $this->object->offsetGet(1);
    $this->assertInstanceOf('UGComponents\Data\Model', $instance);
  }

  /**
   * @covers UGComponents\Data\Collection::offsetSet
   */
  public function testOffsetSet()
  {
    $this->object->offsetSet(null, [
      'post_id' => 4,
      'post_title' => 'Foobar 4',
      'post_detail' => 'foobar 4',
      'post_active' => 0
    ]);

    $this->assertEquals(4, count($this->object));
  }

  /**
   * @covers UGComponents\Data\Collection::offsetUnset
   */
  public function testOffsetUnset()
  {
    $this->object->offsetUnset(2);
    $this->assertEquals(2, count($this->object));
  }

  /**
   * @covers UGComponents\Data\Collection::count
   */
  public function testCount()
  {
    $this->assertEquals(3, count($this->object));
  }

  /**
   * @covers UGComponents\Data\Collection::generator
   */
  public function testGenerator()
  {
    foreach($this->object->generator() as $i => $model) {
      $this->assertInstanceOf('UGComponents\Data\Model', $model);
    }
  }

  /**
   * @covers UGComponents\Data\Collection::bindCallback
   */
  public function testBindCallback()
  {
    $trigger = new StdClass;
    $trigger->success = null;
    $trigger->test = $this;

    $callback = $this->object->bindCallback(function() use ($trigger) {
      $trigger->success = true;
      $trigger->test->assertInstanceOf('UGComponents\Data\Collection', $this);
    });

    $callback();

    $this->assertTrue($trigger->success);
  }

  /**
   * @covers UGComponents\Data\Collection::current
   */
  public function testCurrent()
  {
    $actual = $this->object->current();
    $this->assertEquals(1, $actual['post_id']);
  }

  /**
   * @covers UGComponents\Data\Collection::key
   */
  public function testKey()
  {
    $actual = $this->object->key();
    $this->assertEquals(0, $actual);
  }

  /**
   * @covers UGComponents\Data\Collection::next
   */
  public function testNext()
  {
    $this->object->next();
    $actual = $this->object->current();
    $this->assertEquals(2, $actual['post_id']);
  }

  /**
   * @covers UGComponents\Data\Collection::rewind
   */
  public function testRewind()
  {
    $this->object->rewind();
    $actual = $this->object->current();
    $this->assertEquals(1, $actual['post_id']);
  }

  /**
   * @covers UGComponents\Data\Collection::valid
   */
  public function testValid()
  {
    $this->assertTrue($this->object->valid());
  }

  /**
   * @covers UGComponents\Data\Collection::getEventEmitter
   */
  public function testGetEventEmitter()
  {
    $instance = $this->object->getEventEmitter();
    $this->assertInstanceOf('UGComponents\Event\EventEmitter', $instance);
  }

  /**
   * @covers UGComponents\Data\Collection::on
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

    $this->assertInstanceOf('UGComponents\Data\Collection', $instance);
    $this->assertTrue($trigger->success);
  }

  /**
   * @covers UGComponents\Data\Collection::setEventEmitter
   */
  public function testSetEventEmitter()
  {
    $instance = $this->object->setEventEmitter(new \UGComponents\Event\EventEmitter);
    $this->assertInstanceOf('UGComponents\Data\Collection', $instance);
  }

  /**
   * @covers UGComponents\Data\Collection::trigger
   */
  public function testTrigger()
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

    $this->assertInstanceOf('UGComponents\Data\Collection', $instance);
    $this->assertTrue($trigger->success);
  }

  /**
   * @covers UGComponents\Data\Collection::i
   */
  public function testI()
  {
    $instance1 = Collection::i();
    $this->assertInstanceOf('UGComponents\Data\Collection', $instance1);

    $instance2 = Collection::i();
    $this->assertTrue($instance1 !== $instance2);
  }

  /**
   * @covers UGComponents\Data\Collection::loop
   */
  public function testLoop()
  {
    $self = $this;
    $this->object->loop(function($i) use ($self) {
      $self->assertInstanceOf('UGComponents\Data\Collection', $this);

      if ($i == 2) {
        return false;
      }
    });
  }

  /**
   * @covers UGComponents\Data\Collection::when
   */
  public function testWhen()
  {
    $self = $this;
    $test = 'Good';
    $this->object->when(function() use ($self) {
      $self->assertInstanceOf('UGComponents\Data\Collection', $this);
      return false;
    }, function() use ($self, &$test) {
      $self->assertInstanceOf('UGComponents\Data\Collection', $this);
      $test = 'Bad';
    });

    $this->assertSame('Good', $test);
  }

  /**
   * @covers UGComponents\Data\Collection::getInspectorHandler
   */
  public function testGetInspectorHandler()
  {
    $instance = $this->object->getInspectorHandler();
    $this->assertInstanceOf('UGComponents\Profiler\InspectorInterface', $instance);
  }

  /**
   * @covers UGComponents\Data\Collection::inspect
   */
  public function testInspect()
  {
    ob_start();
    $this->object->inspect('foobar');
    $contents = ob_get_contents();
    ob_end_clean();

    $this->assertEquals(
      '<pre>INSPECTING Variable:</pre><pre>foobar</pre>',
      $contents
    );
  }

  /**
   * @covers UGComponents\Data\Collection::setInspectorHandler
   */
  public function testSetInspectorHandler()
  {
    $instance = $this->object->setInspectorHandler(new \UGComponents\Profiler\InspectorHandler);
    $this->assertInstanceOf('UGComponents\Data\Collection', $instance);
  }

  /**
   * @covers UGComponents\Data\Collection::addLogger
   */
  public function testAddLogger()
  {
    $instance = $this->object->addLogger(function() {});
    $this->assertInstanceOf('UGComponents\Data\Collection', $instance);
  }

  /**
   * @covers UGComponents\Data\Collection::log
   */
  public function testLog()
  {
    $trigger = new StdClass();
    $trigger->success = null;
    $this->object->addLogger(function($trigger) {
      $trigger->success = true;
    })
    ->log($trigger);


    $this->assertTrue($trigger->success);
  }

  /**
   * @covers UGComponents\Data\Collection::loadState
   */
  public function testLoadState()
  {
    $state1 = new Collection(array());
    $state2 = new Collection(array());

    $state1->saveState('state1');
    $state2->saveState('state2');

    $this->assertTrue($state2 === $state1->loadState('state2'));
    $this->assertTrue($state1 === $state2->loadState('state1'));
  }

  /**
   * @covers UGComponents\Data\Collection::saveState
   */
  public function testSaveState()
  {
    $state1 = new Collection(array());
    $state2 = new Collection(array());

    $state1->saveState('state1');
    $state2->saveState('state2');

    $this->assertTrue($state2 === $state1->loadState('state2'));
    $this->assertTrue($state1 === $state2->loadState('state1'));
  }

  /**
   * @covers UGComponents\Data\Collection::__callResolver
   */
  public function test__callResolver()
  {
    $actual = $this->object->addResolver(Collection::class, function() {});
    $this->assertInstanceOf('UGComponents\Data\Collection', $actual);
  }

  /**
   * @covers UGComponents\Data\Collection::addResolver
   */
  public function testAddResolver()
  {
    $actual = $this->object->addResolver(ResolverAddStub::class, function() {});
    $this->assertInstanceOf('UGComponents\Data\Collection', $actual);
  }

  /**
   * @covers UGComponents\Data\Collection::getResolverHandler
   */
  public function testGetResolverHandler()
  {
    $actual = $this->object->getResolverHandler();
    $this->assertInstanceOf('UGComponents\Resolver\ResolverInterface', $actual);
  }

  /**
   * @covers UGComponents\Data\Collection::resolve
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
   * @covers UGComponents\Data\Collection::resolveShared
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
   * @covers UGComponents\Data\Collection::resolveStatic
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
   * @covers UGComponents\Data\Collection::setResolverHandler
   */
  public function testSetResolverHandler()
  {
    $actual = $this->object->setResolverHandler(new \UGComponents\Resolver\ResolverHandler);
    $this->assertInstanceOf('UGComponents\Data\Collection', $actual);
  }
}

if(!class_exists('UGComponents\Data\ResolverCallStub')) {
  class ResolverCallStub
  {
    public function foo($string)
    {
      return $string . 'foo';
    }
  }
}

if(!class_exists('UGComponents\Data\ResolverAddStub')) {
  class ResolverAddStub
  {
    public function foo($string)
    {
      return $string . 'foo';
    }
  }
}

if(!class_exists('UGComponents\Data\ResolverSharedStub')) {
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

if(!class_exists('UGComponents\Data\ResolverStaticStub')) {
  class ResolverStaticStub
  {
    public static function foo($string)
    {
      return $string . 'foo';
    }
  }
}
