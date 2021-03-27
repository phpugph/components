<?php

namespace UGComponents\IO\Middleware;

use PHPUnit\Framework\TestCase;
use UGComponents\IO\Middleware;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 02:11:01.
 */
class IO_PreProcessorTrait_Test extends TestCase
{
  /**
   * @var PreProcessorTrait
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp()
  {
    $this->object = new PreProcessorTraitStub;
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown()
  {
  }

  /**
   * covers UGComponents\IO\PreProcessorTrait::getPreprocessor
   */
  public function testGetPreprocessor()
  {
    $instance = $this->object->getPreprocessor();
    $this->assertInstanceOf('UGComponents\IO\Middleware', $instance);
  }

  /**
   * covers UGComponents\IO\PreProcessorTrait::preprocess
   */
  public function testPreprocess()
  {
    $instance = $this->object->preprocess(function() {});
    $this->assertInstanceOf('UGComponents\IO\Middleware\PreProcessorTraitStub', $instance);
  }

  /**
   * covers UGComponents\IO\PreProcessorTrait::setPreprocessor
   */
  public function testSetPreprocessor()
  {
    $instance = $this->object->setPreprocessor(new Middleware);
    $this->assertInstanceOf('UGComponents\IO\Middleware\PreProcessorTraitStub', $instance);
  }
}

if(!class_exists('UGComponents\IO\PreProcessorTraitStub')) {
  class PreProcessorTraitStub
  {
    use PreProcessorTrait;
  }
}
