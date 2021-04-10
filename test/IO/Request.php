<?php

namespace UGComponents\IO;

use PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-28 at 11:36:33.
 */
class IO_Request_Test extends TestCase
{
  /**
   * @var Request
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp(): void
  {
    $this->object = new Request;

    $this->object->set('cookie', array(
      'foo' => 'bar',
      'bar' => 'foo'
    ));

    $this->object->set('files', array(
      'foo' => 'bar',
      'bar' => 'foo'
    ));

    $this->object->set('get', array(
      'foo' => 'bar',
      'bar' => 'foo'
    ));

    $this->object->set('post', array(
      'foo' => 'bar',
      'bar' => 'foo'
    ));

    $this->object->set('session', array(
      'foo' => 'bar',
      'bar' => 'foo'
    ));

    $this->object->set('server', array(
      'foo' => 'bar',
      'bar' => 'foo'
    ));

    $this->object->set('stage', array(
      'foo' => 'bar',
      'bar' => 'foo'
    ));
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown(): void
  {
  }

  /**
   * @covers UGComponents\IO\AbstractIO::__set
   * @covers UGComponents\IO\AbstractIO::__get
   */
  public function test__SetGet()
  {
    $this->object->__set('foo', 'bar');

    $this->assertEquals('bar', $this->object->__get('foo'));
    $this->assertNull($this->object->__get('bar'));
  }

  /**
   * @covers UGComponents\IO\Request::load
   */
  public function testLoad()
  {
    $instance = $this->object->load();
    $this->assertInstanceOf('UGComponents\IO\Request', $instance);
  }

  /**
   * @covers UGComponents\IO\Request::getArgs
   */
  public function testGetArgs()
  {
    $this->object->set('args', array(1, 2, 3));
    $actual = $this->object->getArgs();
    $this->assertEquals(2, $actual[1]);
  }

  /**
   * @covers UGComponents\IO\Request::setArgs
   */
  public function testSetArgs()
  {
    $this->object->setArgs(array(1, 2, 3));
    $actual = $this->object->getArgs();
    $this->assertEquals(2, $actual[1]);
  }

  /**
   * @covers UGComponents\IO\Request::getContent
   */
  public function testGetContent()
  {
    $this->object->set('body', 'foobar');
    $actual = $this->object->getContent();
    $this->assertEquals('foobar', $actual);
  }

  /**
   * @covers UGComponents\IO\Request::hasContent
   */
  public function testHasContent()
  {
    $this->assertFalse($this->object->hasContent());
    $this->object->set('body', 'foobar');
    $this->assertTrue($this->object->hasContent());
  }

  /**
   * @covers UGComponents\IO\Request::setContent
   */
  public function testSetContent()
  {
    $instance = $this->object->setContent('foobar');
    $this->assertInstanceOf('UGComponents\IO\Request', $instance);
  }

  /**
   * @covers UGComponents\IO\Request::getCookies
   */
  public function testGetCookies()
  {
    $this->assertEquals('bar', $this->object->getCookies('foo'));
  }

  /**
   * @covers UGComponents\IO\Request::hasCookies
   */
  public function testHasCookies()
  {
    $this->assertTrue($this->object->hasCookies('foo'));
    $this->assertFalse($this->object->hasCookies('zoo'));
  }

  /**
   * covers UGComponents\IO\Request::removeCookies
   */
  public function testRemoveCookies()
  {
    $this->object->removeCookies('foo');
    $this->assertFalse($this->object->hasCookies('foo'));
  }

  /**
   * @covers UGComponents\IO\Request::setCookies
   */
  public function testSetCookies()
  {
    $instance = $this->object->setCookies(array(
      'foo' => 'bar',
      'bar' => 'foo'
    ));

    $this->assertInstanceOf('UGComponents\IO\Request', $instance);
  }

  /**
   * @covers UGComponents\IO\Request::getFiles
   */
  public function testGetFiles()
  {
    $this->assertEquals('bar', $this->object->getFiles('foo'));
  }

  /**
   * @covers UGComponents\IO\Request::hasFiles
   */
  public function testHasFiles()
  {
    $this->assertTrue($this->object->hasFiles('foo'));
    $this->assertFalse($this->object->hasFiles('zoo'));
  }

  /**
   * covers UGComponents\IO\Request::removeFiles
   */
  public function testRemoveFiles()
  {
    $this->object->removeFiles('foo');
    $this->assertFalse($this->object->hasFiles('foo'));
  }

  /**
   * @covers UGComponents\IO\Request::setFiles
   */
  public function testSetFiles()
  {
    $instance = $this->object->setFiles(array(
      'foo' => 'bar',
      'bar' => 'foo'
    ));

    $this->assertInstanceOf('UGComponents\IO\Request', $instance);
  }

  /**
   * @covers UGComponents\IO\Request::getGet
   */
  public function testGetGet()
  {
    $this->assertEquals('bar', $this->object->getGet('foo'));
  }

  /**
   * @covers UGComponents\IO\Request::hasGet
   */
  public function testHasGet()
  {
    $this->assertTrue($this->object->hasGet('foo'));
    $this->assertFalse($this->object->hasGet('zoo'));
  }

  /**
   * covers UGComponents\IO\Request::removeGet
   */
  public function testRemoveGet()
  {
    $this->object->removeGet('foo');
    $this->assertFalse($this->object->hasGet('foo'));
  }

  /**
   * @covers UGComponents\IO\Request::setGet
   */
  public function testSetGet()
  {
    $instance = $this->object->setGet(array(
      'foo' => 'bar',
      'bar' => 'foo'
    ));

    $this->assertInstanceOf('UGComponents\IO\Request', $instance);
  }

  /**
   * @covers UGComponents\IO\Request::getPost
   */
  public function testGetPost()
  {
    $this->assertEquals('bar', $this->object->getPost('foo'));
  }

  /**
   * @covers UGComponents\IO\Request::hasPost
   */
  public function testHasPost()
  {
    $this->assertTrue($this->object->hasPost('foo'));
    $this->assertFalse($this->object->hasPost('zoo'));
  }

  /**
   * covers UGComponents\IO\Request::removePost
   */
  public function testRemovePost()
  {
    $this->object->removePost('foo');
    $this->assertFalse($this->object->hasPost('foo'));
  }

  /**
   * @covers UGComponents\IO\Request::setPost
   */
  public function testSetPost()
  {
    $instance = $this->object->setPost(array(
      'foo' => 'bar',
      'bar' => 'foo'
    ));

    $this->assertInstanceOf('UGComponents\IO\Request', $instance);
  }

  /**
   * @covers UGComponents\IO\Request::getRoute
   */
  public function testGetRoute()
  {
    $this->object->set('route', array(
      'foo' => 'bar',
      'bar' => 'foo'
    ));

    $this->assertEquals('bar', $this->object->getRoute('foo'));
  }

  /**
   * covers UGComponents\IO\Request::getParameters
   */
  public function testGetParameters()
  {
    $this->object->set('route', array(
      'foo' => 'bar',
      'parameters' => array('foo' => 'bar')
    ));

    $this->assertEquals('bar', $this->object->getParameters('foo'));
  }

  /**
   * @covers UGComponents\IO\Request::getVariables
   */
  public function testGetVariables()
  {
    $this->object->set('route', array(
      'foo' => 'bar',
      'variables' => array('foo', 'bar')
    ));

    $this->assertEquals('bar', $this->object->getVariables(1));
  }

  /**
   * @covers UGComponents\IO\Request::setRoute
   */
  public function testSetRoute()
  {
    $instance = $this->object->setRoute(array(
      'foo' => 'bar',
      'bar' => 'foo'
    ));

    $this->assertInstanceOf('UGComponents\IO\Request', $instance);
  }

  /**
   * @covers UGComponents\IO\Request::getMethod
   */
  public function testGetMethod()
  {
    $this->object->set('method', 'foobar');
    $this->assertEquals('FOOBAR', $this->object->getMethod());
  }

  /**
   * @covers UGComponents\IO\Request::getPath
   */
  public function testGetPath()
  {
    $this->object->setPath('/foo/bar');
    $this->assertEquals('/foo/bar', $this->object->getPath('string'));
  }

  /**
   * @covers UGComponents\IO\Request::getQuery
   */
  public function testGetQuery()
  {
    $this->object->set('query', 'foobar');
    $this->assertEquals('foobar', $this->object->getQuery());
  }

  /**
   * @covers UGComponents\IO\Request::getServer
   */
  public function testGetServer()
  {
    $this->assertEquals('bar', $this->object->getServer('foo'));
  }

  /**
   * @covers UGComponents\IO\Request::hasServer
   */
  public function testHasServer()
  {
    $this->assertTrue($this->object->hasServer('foo'));
    $this->assertFalse($this->object->hasServer('zoo'));
  }

  /**
   * @covers UGComponents\IO\Request::isMethod
   */
  public function testIsMethod()
  {
    $this->assertFalse($this->object->isMethod('foobar'));

    $this->object->setMethod('foobar');
    $this->assertTrue($this->object->isMethod('foobar'));
  }

  /**
   * @covers UGComponents\IO\Request::setMethod
   */
  public function testSetMethod()
  {
    $instance = $this->object->setMethod('foobar');

    $this->assertInstanceOf('UGComponents\IO\Request', $instance);
  }

  /**
   * @covers UGComponents\IO\Request::setPath
   */
  public function testSetPath()
  {
    $instance = $this->object->setPath('foobar');

    $this->assertInstanceOf('UGComponents\IO\Request', $instance);
  }

  /**
   * @covers UGComponents\IO\Request::setQuery
   */
  public function testSetQuery()
  {
    $instance = $this->object->setQuery('foobar');

    $this->assertInstanceOf('UGComponents\IO\Request', $instance);
  }

  /**
   * @covers UGComponents\IO\Request::setServer
   */
  public function testSetServer()
  {
    $instance = $this->object->setServer(array(
      'foo' => 'bar',
      'bar' => 'foo'
    ));

    $this->assertInstanceOf('UGComponents\IO\Request', $instance);
  }

  /**
   * @covers UGComponents\IO\Request::getSession
   */
  public function testGetSession()
  {
    $this->assertEquals('bar', $this->object->getSession('foo'));
  }

  /**
   * @covers UGComponents\IO\Request::hasSession
   */
  public function testHasSession()
  {
    $this->assertTrue($this->object->hasSession('foo'));
    $this->assertFalse($this->object->hasSession('zoo'));
  }

  /**
   * covers UGComponents\IO\Request::removeSession
   */
  public function testRemoveSession()
  {
    $this->object->removeSession('foo');
    $this->assertFalse($this->object->hasSession('foo'));
  }

  /**
   * @covers UGComponents\IO\Request::setSession
   */
  public function testSetSession()
  {
    $session = array(
      'foo' => 'bar',
      'bar' => 'foo'
    );

    $instance = $this->object->setSession($session);

    $this->assertInstanceOf('UGComponents\IO\Request', $instance);
  }

	/**
   * covers UGComponents\IO\Request\StageTrait::getStage
   */
  public function testGetStage()
  {
    $this->assertEquals('bar', $this->object->getStage('foo'));
  }

  /**
   * covers UGComponents\IO\Request\StageTrait::hasStage
   */
  public function testHasStage()
  {
    $this->assertTrue($this->object->hasStage('foo'));
    $this->assertFalse($this->object->hasStage('zoo'));
  }

  /**
   * covers UGComponents\IO\Request\StageTrait::removeStage
   */
  public function testRemoveStage()
  {
    $this->object->removeStage('foo');
    $this->assertFalse($this->object->hasStage('foo'));
  }

  /**
   * covers UGComponents\IO\Request\StageTrait::setStage
   */
  public function testSetStage()
  {
    $instance = $this->object->setStage(array(
      'foo' => 'bar',
      'bar' => 'foo'
    ));

    $this->assertInstanceOf('UGComponents\IO\Request', $instance);
  }

  /**
   * Tests state loading
   */
  public function testStates()
  {
    //case study to solve for using the request variable
    $request = $this->object->saveState('test1');
    $this->assertEquals('bar', $request->getStage('foo'));

    $request = Request::i()->setStage('foo', 'zoo');
    $this->assertEquals('zoo', $request->getStage('foo'));

    $this->assertEquals('bar', $request->loadState('test1')->getStage('foo'));

    $request
      ->saveState('main_request')
      ->setStage('foo', 'zap')
      ->saveState('sub_request', new Request)
      ->loadState('sub_request')
      ->setStage('foo', 'baz');

    $this->assertEquals('baz', $request->loadState('sub_request')->getStage('foo'));
    $this->assertEquals('zap', $request->loadState('main_request')->getStage('foo'));
  }
}
