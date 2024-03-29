<?php

namespace UGComponents\IO\Request;

use PHPUnit\Framework\TestCase;
use UGComponents\Data\Registry;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-28 at 11:36:34.
 */
class SessionTraitTest extends TestCase
{
  /**
   * @var SessionTrait
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp(): void
  {
    $this->object = new SessionTraitStub;

    $this->object->set('session', array(
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
   * @covers UGComponents\IO\Request\SessionTrait::getSession
   */
  public function testGetSession()
  {
    $this->assertEquals('bar', $this->object->getSession('foo'));
  }

  /**
   * @covers UGComponents\IO\Request\SessionTrait::hasSession
   */
  public function testHasSession()
  {
    $this->assertTrue($this->object->hasSession('foo'));
    $this->assertFalse($this->object->hasSession('zoo'));
  }

  /**
   * @covers UGComponents\IO\Request\SessionTrait::removeSession
   */
  public function testRemoveSession()
  {
    $this->object->removeSession('foo');
    $this->assertFalse($this->object->hasSession('foo'));
  }

  /**
   * @covers UGComponents\IO\Request\SessionTrait::setSession
   */
  public function testSetSession()
  {
    $session = array(
      'foo' => 'bar',
      'bar' => 'foo'
    );

    $instance = $this->object->setSession($session);
    $this->assertInstanceOf('UGComponents\IO\Request\SessionTraitStub', $instance);

    $instance = $this->object->setSession('zoo');
    $this->assertInstanceOf('UGComponents\IO\Request\SessionTraitStub', $instance);

    $instance = $this->object->setSession('zoo', 'foo', 'bar');
    $this->assertInstanceOf('UGComponents\IO\Request\SessionTraitStub', $instance);
  }
}

if(!class_exists('UGComponents\IO\Request\SessionTraitStub')) {
  class SessionTraitStub extends Registry
  {
    use SessionTrait;
  }
}
