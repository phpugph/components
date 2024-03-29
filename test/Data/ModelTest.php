<?php

namespace UGComponents\Data;

use StdClass;
use PHPUnit\Framework\TestCase;
use UGComponents\Data\Model\ModelException;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 02:11:00.
 */
class ModelTest extends TestCase
{
  /**
   * @var Model
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp(): void
  {
    $this->object = new Model([
			'post_id' => 1,
			'post_title' => 'Foobar 1',
			'post_detail' => 'foobar 1',
			'post_active' => 1
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
   * @covers UGComponents\Data\Model::__construct
   */
  public function test__construct()
  {
    $actual = $this->object->__construct([
			'post_id' => 1,
			'post_title' => 'Foobar 1',
			'post_detail' => 'foobar 1',
			'post_active' => 1
		]);
		
		$this->assertNull($actual);
  }

  /**
   * @covers UGComponents\Data\Model::__call
   */
  public function test__call()
  {
    $instance = $this->object->__call('setPostTitle', array('Foobar 4'));
		$this->assertInstanceOf('UGComponents\Data\Model', $instance);
		
    $actual = $this->object->__call('getPostTitle', array());
		
		$this->assertEquals('Foobar 4', $actual);
		
		$instance = $this->object->__call(Collection::class, array());
		$this->assertInstanceOf('UGComponents\Data\Collection', $instance);
		
		$thrown = false;
		try {
			$this->object->__call('foobar', array());
		} catch(ModelException $e) {
			$thrown = true;
		}
		
		$this->assertTrue($thrown);
  }

  /**
   * @covers UGComponents\Data\Model::get
   */
  public function testGet()
  {
    $actual = $this->object->get();
		
		$this->assertEquals('Foobar 1', $actual['post_title']);
  }

  /**
   * @covers UGComponents\Data\Model::set
   */
  public function testSet()
  {
    $instance = $this->object->set([
			'post_id' => 1,
			'post_title' => 'Foobar 1',
			'post_detail' => 'foobar 1',
			'post_active' => 1
		]);
     
		
		$this->assertInstanceOf('UGComponents\Data\Model', $instance);
  }

  /**
   * @covers UGComponents\Data\Model::offsetExists
   */
  public function testOffsetExists()
  {
    
    $this->assertTrue($this->object->offsetExists('post_id'));
    $this->assertFalse($this->object->offsetExists(3));
  }

  /**
   * @covers UGComponents\Data\Model::offsetGet
   */
  public function testOffsetGet()
  {
    $actual = $this->object->offsetGet('post_id');
		$this->assertEquals(1, $actual);
  }

  /**
   * @covers UGComponents\Data\Model::offsetSet
   */
  public function testOffsetSet()
  {
    $this->object->offsetSet('post_id', 2);
		
		$this->assertEquals(2, $this->object['post_id']);
  }

  /**
   * @covers UGComponents\Data\Model::offsetUnset
   */
  public function testOffsetUnset()
  {
		$this->object->offsetUnset('post_id');
		$this->assertFalse(isset($this->object['post_id']));
  }

  /**
   * @covers UGComponents\Data\Model::current
   */
  public function testCurrent()
  {
    $actual = $this->object->current();
  	$this->assertEquals(1, $actual);
  }

  /**
   * @covers UGComponents\Data\Model::key
   */
  public function testKey()
  {
    $actual = $this->object->key();
  	$this->assertEquals('post_id', $actual);
  }

  /**
   * @covers UGComponents\Data\Model::next
   */
  public function testNext()
  {
		$this->object->next();
    $actual = $this->object->current();
  	$this->assertEquals('Foobar 1', $actual);
  }

  /**
   * @covers UGComponents\Data\Model::rewind
   */
  public function testRewind()
  {
		$this->object->rewind();
    $actual = $this->object->current();
  	$this->assertEquals(1, $actual);
  }

  /**
   * @covers UGComponents\Data\Model::valid
   */
  public function testValid()
  {
    $this->assertTrue($this->object->valid());
  }

  /**
   * @covers UGComponents\Data\Model::count
   */
  public function testCount()
  {
    $this->assertEquals(4, count($this->object));
  }

  /**
   * @covers UGComponents\Data\Model::getDot
   */
  public function testGetDot()
  {
    $this->assertEquals(1, $this->object->getDot('post_id'));
  }

  /**
   * @covers UGComponents\Data\Model::isDot
   */
  public function testIsDot()
  {
		$this->assertTrue($this->object->isDot('post_id'));
  }

  /**
   * @covers UGComponents\Data\Model::removeDot
   */
  public function testRemoveDot()
  {
		$this->object->removeDot('post_id');
		$this->assertFalse($this->object->isDot('post_id'));
  }

  /**
   * @covers UGComponents\Data\Model::setDot
   */
  public function testSetDot()
  {
		$this->object->setDot('post_id', 2);
    $this->assertEquals(2, $this->object->getDot('post_id'));
  }

  /**
   * @covers UGComponents\Data\Model::__callData
   */
  public function test__callData()
  {
    $instance = $this->object->__call('setPostTitle', array('Foobar 4'));
		$this->assertInstanceOf('UGComponents\Data\Model', $instance);
		
    $actual = $this->object->__call('getPostTitle', array());
		
		$this->assertEquals('Foobar 4', $actual);
  }

  /**
   * @covers UGComponents\Data\Model::__get
   */
  public function test__get()
  {
    $actual = $this->object->post_title;
		$this->assertEquals('Foobar 1', $actual);
  }

  /**
   * @covers UGComponents\Data\Model::__getData
   */
  public function test__getData()
  {
    $actual = $this->object->post_title;
		$this->assertEquals('Foobar 1', $actual);
  }

  /**
   * @covers UGComponents\Data\Model::__set
   * @todo   Implement test__set().
   */
  public function test__set()
  {
    $this->object->post_title = 'Foobar 4';
    $actual = $this->object->post_title;
		
		$this->assertEquals('Foobar 4', $actual);
  }

  /**
   * @covers UGComponents\Data\Model::__setData
   * @todo   Implement test__setData().
   */
  public function test__setData()
  {
    $this->object->post_title = 'Foobar 4';
    $actual = $this->object->post_title;
		
		$this->assertEquals('Foobar 4', $actual);
  }

  /**
   * @covers UGComponents\Data\Model::__toString
   * @todo   Implement test__toString().
   */
  public function test__toString()
  {
    $this->assertEquals(json_encode([
			'post_id' => 1,
			'post_title' => 'Foobar 1',
			'post_detail' => 'foobar 1',
			'post_active' => 1
		], JSON_PRETTY_PRINT), (string) $this->object);
  }

  /**
   * @covers UGComponents\Data\Model::__toStringData
   */
  public function test__toStringData()
  {
    $this->assertEquals(json_encode([
			'post_id' => 1,
			'post_title' => 'Foobar 1',
			'post_detail' => 'foobar 1',
			'post_active' => 1
		], JSON_PRETTY_PRINT), (string) $this->object);
  }

  /**
   * @covers UGComponents\Data\Model::generator
   */
  public function testGenerator()
  {
    foreach($this->object->generator() as $i => $value);
		
		$this->assertEquals('post_active', $i);
  }

  /**
   * @covers UGComponents\Data\Model::getEventEmitter
   */
  public function testGetEventEmitter()
  {
		$instance = $this->object->getEventEmitter();
		$this->assertInstanceOf('UGComponents\Event\EventEmitter', $instance);
  }

  /**
   * @covers UGComponents\Data\Model::on
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
		
		$this->assertInstanceOf('UGComponents\Data\Model', $instance);
		$this->assertTrue($trigger->success);
  }

  /**
   * @covers UGComponents\Data\Model::setEventEmitter
   */
  public function testSetEventEmitter()
  {
    $instance = $this->object->setEventEmitter(new \UGComponents\Event\EventEmitter);
		$this->assertInstanceOf('UGComponents\Data\Model', $instance);
  }

  /**
   * @covers UGComponents\Data\Model::emit
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
		
		$this->assertInstanceOf('UGComponents\Data\Model', $instance);
		$this->assertTrue($trigger->success);
  }

  /**
   * @covers UGComponents\Data\Model::i
   */
  public function testI()
  {
    $instance1 = Model::i();
		$this->assertInstanceOf('UGComponents\Data\Model', $instance1);
		
		$instance2 = Model::i();
		$this->assertTrue($instance1 !== $instance2);
  }

  /**
   * @covers UGComponents\Data\Model::loop
   */
  public function testLoop()
  {
    $self = $this;
    $this->object->loop(function($i) use ($self) {
      $self->assertInstanceOf('UGComponents\Data\Model', $this);
      
      if ($i == 2) {
        return false;
      }
    });
  }

  /**
   * @covers UGComponents\Data\Model::when
   */
  public function testWhen()
  {
    $self = $this;
    $test = 'Good';
    $this->object->when(function() use ($self) {
      $self->assertInstanceOf('UGComponents\Data\Model', $this);
      return false;
    }, function() use ($self, &$test) {
      $self->assertInstanceOf('UGComponents\Data\Model', $this);
      $test = 'Bad';
    });
    
    $this->assertSame('Good', $test);
  }

  /**
   * @covers UGComponents\Data\Model::getInspectorHandler
   */
  public function testGetInspectorHandler()
  {
    $instance = $this->object->getInspectorHandler();
		$this->assertInstanceOf('UGComponents\Profiler\InspectorInterface', $instance);
  }

  /**
   * @covers UGComponents\Data\Model::inspect
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
   * @covers UGComponents\Data\Model::setInspectorHandler
   */
  public function testSetInspectorHandler()
  {
    $instance = $this->object->setInspectorHandler(new \UGComponents\Profiler\InspectorHandler);
		$this->assertInstanceOf('UGComponents\Data\Model', $instance);
  }

  /**
   * @covers UGComponents\Data\Model::addLogger
   */
  public function testAddLogger()
  {
    $instance = $this->object->addLogger(function() {});
		$this->assertInstanceOf('UGComponents\Data\Model', $instance);
  }

  /**
   * @covers UGComponents\Data\Model::log
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
   * @covers UGComponents\Data\Model::loadState
   * @todo   Implement testLoadState().
   */
  public function testLoadState()
  {
    $state1 = new Model(array());
		$state2 = new Model(array());
		
		$state1->saveState('state1');
		$state2->saveState('state2');
		
		$this->assertTrue($state2 === $state1->loadState('state2'));
		$this->assertTrue($state1 === $state2->loadState('state1'));
  }

  /**
   * @covers UGComponents\Data\Model::saveState
   */
  public function testSaveState()
  {
		$state1 = new Model(array());
		$state2 = new Model(array());
		
		$state1->saveState('state1');
		$state2->saveState('state2');
		
		$this->assertTrue($state2 === $state1->loadState('state2'));
		$this->assertTrue($state1 === $state2->loadState('state1'));
  }

  /**
   * @covers UGComponents\Data\Model::__callResolver
   */
  public function test__callResolver()
  {
    $actual = $this->object->addResolver(Model::class, function() {});
		$this->assertInstanceOf('UGComponents\Data\Model', $actual);
  }

  /**
   * @covers UGComponents\Data\Model::addResolver
   */
  public function testAddResolver()
  {
    $actual = $this->object->addResolver(Model::class, function() {});
		$this->assertInstanceOf('UGComponents\Data\Model', $actual);
  }

  /**
   * @covers UGComponents\Data\Model::getResolverHandler
   */
  public function testGetResolverHandler()
  {
    $actual = $this->object->getResolverHandler();
		$this->assertInstanceOf('UGComponents\Resolver\ResolverInterface', $actual);
  }

  /**
   * @covers UGComponents\Data\Model::resolve
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
   * @covers UGComponents\Data\Model::resolveShared
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
   * @covers UGComponents\Data\Model::resolveStatic
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
   * @covers UGComponents\Data\Model::setResolverHandler
   */
  public function testSetResolverHandler()
  {
    $actual = $this->object->setResolverHandler(new \UGComponents\Resolver\ResolverHandler);
		$this->assertInstanceOf('UGComponents\Data\Model', $actual);
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
