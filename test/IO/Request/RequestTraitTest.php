<?php

namespace UGComponents\IO\Request;

use PHPUnit\Framework\TestCase;
use UGComponents\IO\Request;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 02:11:01.
 */
class RequestTraitTest extends TestCase
{
  /**
   * @var RequestTrait
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp(): void
  {
    $this->object = new RequestTraitStub;
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown(): void
  {
  }

  /**
   * covers UGComponents\IO\RequestTrait::getRequest
   */
  public function testGetRequest()
  {
    $instance = $this->object->getRequest();
    $this->assertInstanceOf('UGComponents\IO\Request', $instance);
  }

  /**
   * covers UGComponents\IO\RequestTrait::setRequest
   */
  public function testSetRequest()
  {
    $instance = $this->object->setRequest(new Request);
    $this->assertInstanceOf('UGComponents\IO\Request\RequestTraitStub', $instance);
  }
}

if(!class_exists('UGComponents\IO\Request\RequestTraitStub')) {
  class RequestTraitStub
  {
    use RequestTrait;
  }
}