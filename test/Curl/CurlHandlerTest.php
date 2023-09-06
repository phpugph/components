<?php

namespace UGComponents\Curl;

use StdClass;
use PHPUnit\Framework\TestCase;
use UGComponents\Resolver\ResolverHandler;
use UGComponents\Profiler\InspectorHandler;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 02:10:59.
 */
class CurlHandlerTest extends TestCase
{
  /**
   * @var CurlHandler
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp(): void
  {
    $this->object = new CurlHandler(function($options) {
      $options['response'] = 'foobar';
      return $options;
    });
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown(): void
  {
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::__call
   */
  public function test__call()
  {
    //CURLOPT_FOLLOWLOCATION
    $instance = $this->object->__call('setFollowLocation', [true]);
    $this->assertInstanceOf('UGComponents\Curl\CurlHandler', $instance);

    $thrown = false;
    try {
      $this->object->__call('foobar', array());
    } catch(CurlException $e) {
      $thrown = true;
    }

    $this->assertTrue($thrown);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::getDomDocumentResponse
   */
  public function testGetDomDocumentResponse()
  {
    $this->object = new CurlHandler(function($options) {
      return array('response' => '<?xml version="1.0" encoding="UTF-8"?>
      <foo>Bar</foo>');
    });

    $instance = $this->object->getDomDocumentResponse();

    $this->assertInstanceOf('DOMDocument', $instance);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::getJsonResponse
   */
  public function testGetJsonResponse()
  {
    $this->object = new CurlHandler(function($options) {
      return array('response' => '{"foo":"bar"}');
    });

    $actual = $this->object->getJsonResponse();

    $this->assertEquals('bar', $actual['foo']);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::getMeta
   */
  public function testGetMeta()
  {
    $actual = $this->object->getMeta();

    $this->assertTrue(is_array($actual));
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::getQueryResponse
   */
  public function testGetQueryResponse()
  {
    $this->object = new CurlHandler(function($options) {
      return array('response' => 'foo=bar&bar=zoo');
    });

    $actual = $this->object->getQueryResponse();

    $this->assertEquals('bar', $actual['foo']);
    $this->assertEquals('zoo', $actual['bar']);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::getResponse
   */
  public function testGetResponse()
  {
    $actual = $this->object->getResponse();

    $this->assertEquals('foobar', $actual);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::getSimpleXmlResponse
   * @todo   Implement testGetSimpleXmlResponse().
   */
  public function testGetSimpleXmlResponse()
  {
    $this->object = new CurlHandler(function($options) {
      return array('response' => '<?xml version="1.0" encoding="UTF-8"?>
      <foo>Bar</foo>');
    });

    $instance = $this->object->getSimpleXmlResponse();

    $this->assertInstanceOf('SimpleXMLElement', $instance);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::offsetExists
   */
  public function testOffsetExists()
  {
    //CURLOPT_FOLLOWLOCATION
    $actual = $this->object->offsetExists('foobar');
    $this->assertFalse($actual);

    $this->object->setFollowlocation(true);
    $actual = $this->object->offsetExists('CURLOPT_FOLLOWLOCATION');
    $this->assertTrue($actual);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::offsetGet
   */
  public function testOffsetGet()
  {
    //CURLOPT_FOLLOWLOCATION
    $actual = $this->object->offsetGet('foobar');
    $this->assertNull($actual);

    $this->object->offsetSet('FOLLOWLOCATION', 1);
    $actual = $this->object->offsetGet('FOLLOWLOCATION');
    $this->assertEquals(1, $actual);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::offsetSet
   */
  public function testOffsetSet()
  {
    $this->object->offsetSet('FOLLOWLOCATION', 1);
    $actual = $this->object->offsetGet('FOLLOWLOCATION');
    $this->assertEquals(1, $actual);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::offsetUnset
   */
  public function testOffsetUnset()
  {

    $this->object->offsetSet('FOLLOWLOCATION', 1);
    $this->object->offsetUnset('FOLLOWLOCATION');
    $actual = $this->object->offsetGet('FOLLOWLOCATION');
    $this->assertNull($actual);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::__construct
   * @covers UGComponents\Curl\CurlHandler::send
   * @covers UGComponents\Curl\CurlHandler::getMeta
   * @covers UGComponents\Curl\CurlHandler::addHeaders
   */
  public function testSend()
  {
    $instance = $this->object->send();
    $this->assertInstanceOf('UGComponents\Curl\CurlHandler', $instance);

    $meta = $this->object->getMeta('response');
    $this->assertEquals('foobar', $meta);

    $this->object->setHeaders(array('Expect'));
    $this->object->setPostFields(['foo' => 'bar']);
    $instance = $this->object->send();
    $this->assertInstanceOf('UGComponents\Curl\CurlHandler', $instance);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::setCustomGet
   */
  public function testSetCustomGet()
  {
    $instance = $this->object->setCustomGet();
    $this->assertInstanceOf('UGComponents\Curl\CurlHandler', $instance);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::setCustomPost
   */
  public function testSetCustomPost()
  {
    $instance = $this->object->setCustomPost();
    $this->assertInstanceOf('UGComponents\Curl\CurlHandler', $instance);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::setCustomOptions
   */
  public function testSetCustomOptions()
  {
    $instance = $this->object->setCustomOptions();
    $this->assertInstanceOf('UGComponents\Curl\CurlHandler', $instance);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::setCustomPatch
   */
  public function testSetCustomPatch()
  {
    $instance = $this->object->setCustomPatch();
    $this->assertInstanceOf('UGComponents\Curl\CurlHandler', $instance);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::setCustomPut
   */
  public function testSetCustomPut()
  {
    $instance = $this->object->setCustomPut();
    $this->assertInstanceOf('UGComponents\Curl\CurlHandler', $instance);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::setCustomDelete
   */
  public function testSetCustomDelete()
  {
    $instance = $this->object->setCustomDelete();
    $this->assertInstanceOf('UGComponents\Curl\CurlHandler', $instance);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::setPostFields
   */
  public function testSetPostFields()
  {
    $instance = $this->object->setPostFields('foo=bar');
    $this->assertInstanceOf('UGComponents\Curl\CurlHandler', $instance);

    $instance = $this->object->setPostFields(['foo' => 'bar']);
    $this->assertInstanceOf('UGComponents\Curl\CurlHandler', $instance);

    $instance = $this->object->setPostFields(['foo' => 'bar'], 'json');
    $this->assertInstanceOf('UGComponents\Curl\CurlHandler', $instance);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::setHeaders
   */
  public function testSetHeaders()
  {
    $instance = $this->object->setHeaders(array('Expect'));
    $this->assertInstanceOf('UGComponents\Curl\CurlHandler', $instance);

    $instance = $this->object->setHeaders('Foo', 'Bar');
    $this->assertInstanceOf('UGComponents\Curl\CurlHandler', $instance);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::verifyHost
   */
  public function testVerifyHost()
  {
    $instance = $this->object->verifyHost();
    $this->assertInstanceOf('UGComponents\Curl\CurlHandler', $instance);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::verifyPeer
   */
  public function testVerifyPeer()
  {
    $instance = $this->object->verifyPeer();
    $this->assertInstanceOf('UGComponents\Curl\CurlHandler', $instance);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::i
   */
  public function testI()
  {
    $instance1 = CurlHandler::i();

    $this->assertInstanceOf('UGComponents\Curl\CurlHandler', $instance1);

    $instance2 = CurlHandler::i();

    $this->assertTrue($instance1 !== $instance2);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::loop
   */
  public function testLoop()
  {
    $self = $this;
    $this->object->loop(function($i) use ($self) {
      $self->assertInstanceOf('UGComponents\Curl\CurlHandler', $this);

      if ($i == 2) {
        return false;
      }
    });
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::when
   */
  public function testWhen()
  {
    $self = $this;
    $test = 'Good';
    $this->object->when(function() use ($self) {
      $self->assertInstanceOf('UGComponents\Curl\CurlHandler', $this);
      return false;
    }, function() use ($self, &$test) {
      $self->assertInstanceOf('UGComponents\Curl\CurlHandler', $this);
      $test = 'Bad';
    });
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::getInspectorHandler
   */
  public function testGetInspectorHandler()
  {
    $instance = $this->object->getInspectorHandler();
    $this->assertInstanceOf('UGComponents\Profiler\InspectorHandler', $instance);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::inspect
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
   * @covers UGComponents\Curl\CurlHandler::setInspectorHandler
   */
  public function testSetInspectorHandler()
  {
    $instance = $this->object->setInspectorHandler(new InspectorHandler);
    $this->assertInstanceOf('UGComponents\Curl\CurlHandler', $instance);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::addLogger
   */
  public function testAddLogger()
  {
    $instance = $this->object->addLogger(function() {});
    $this->assertInstanceOf('UGComponents\Curl\CurlHandler', $instance);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::log
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
   * @covers UGComponents\Curl\CurlHandler::loadState
   */
  public function testLoadState()
  {
    $state1 = new CurlHandler();
    $state2 = new CurlHandler();

    $state1->saveState('state1');
    $state2->saveState('state2');

    $this->assertTrue($state2 === $state1->loadState('state2'));
    $this->assertTrue($state1 === $state2->loadState('state1'));
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::saveState
   */
  public function testSaveState()
  {
    $state1 = new CurlHandler();
    $state2 = new CurlHandler();

    $state1->saveState('state1');
    $state2->saveState('state2');

    $this->assertTrue($state2 === $state1->loadState('state2'));
    $this->assertTrue($state1 === $state2->loadState('state1'));
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::__callResolver
   */
  public function test__callResolver()
  {
    $actual = $this->object->addResolver(ResolverCallStub::class, function() {});
    $this->assertInstanceOf('UGComponents\Curl\CurlHandler', $actual);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::addResolver
   */
  public function testAddResolver()
  {
    $actual = $this->object->addResolver(ResolverCallStub::class, function() {});
    $this->assertInstanceOf('UGComponents\Curl\CurlHandler', $actual);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::getResolverHandler
   */
  public function testGetResolverHandler()
  {
    $actual = $this->object->getResolverHandler();
    $this->assertInstanceOf('UGComponents\Resolver\ResolverHandler', $actual);
  }

  /**
   * @covers UGComponents\Curl\CurlHandler::resolve
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
   * @covers UGComponents\Curl\CurlHandler::resolveShared
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
   * @covers UGComponents\Curl\CurlHandler::resolveStatic
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
   * @covers UGComponents\Curl\CurlHandler::setResolverHandler
   */
  public function testSetResolverHandler()
  {
    $actual = $this->object->setResolverHandler(new ResolverHandler);
    $this->assertInstanceOf('UGComponents\Curl\CurlHandler', $actual);
  }
}

if(!class_exists('UGComponents\Curl\ResolverCallStub')) {
  class ResolverCallStub
  {
    public function foo($string)
    {
      return $string . 'foo';
    }
  }
}

if(!class_exists('UGComponents\Curl\ResolverAddStub')) {
  class ResolverAddStub
  {
    public function foo($string)
    {
      return $string . 'foo';
    }
  }
}

if(!class_exists('UGComponents\Curl\ResolverSharedStub')) {
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

if(!class_exists('UGComponents\Curl\ResolverStaticStub')) {
  class ResolverStaticStub
  {
    public static function foo($string)
    {
      return $string . 'foo';
    }
  }
}