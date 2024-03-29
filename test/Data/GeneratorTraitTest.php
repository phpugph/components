<?php

namespace UGComponents\Data;

use PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 02:11:00.
 */
class GeneratorTraitTest extends TestCase
{
  /**
   * @var GeneratorTrait
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp(): void
  {
    $this->object = new GeneratorTraitStub;
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown(): void
  {
  }

  /**
   * @covers UGComponents\Data\GeneratorTrait::generator
   */
  public function testGenerator()
  {
    foreach($this->object->generator() as $i => $value);
		$this->assertEquals('bar', $i);
  }
}

if(!class_exists('UGComponents\Data\GeneratorTraitStub')) {
	class GeneratorTraitStub
	{
		use GeneratorTrait;
		
		protected $data = array(
			'foo' => 'bar',
			'bar' => 'foo'
		);
	}
}
