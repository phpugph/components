<?php

namespace UGComponents\Data;

use PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonClone on 2016-07-27 at 02:11:00.
 */
class CloneTraitTest extends TestCase
{
  /**
   * @var CloneTrait
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp(): void
  {
    $this->object = new CloneTraitStub;
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown(): void
  {
  }

  /**
   * @covers UGComponents\Data\CloneTrait::clone
   */
  public function testClone()
  {
    $clone = $this->object->clone(true);
    $actual = $clone->set()->get();
    $this->assertEquals('foo2', $actual['bar']['zoo']);
    $this->assertEquals('foo', $this->object->get()['bar']['zoo']);
  }
}

if(!class_exists('UGComponents\Data\CloneTraitStub')) {
  class CloneTraitStub
  {
    use CloneTrait;

    protected $data = [
      'foo' => 'bar',
      'bar' => [
        'zoo' => 'foo'
      ]
    ];

    public function get()
    {
      return $this->data;
    }

    public function purge()
    {
    }

    public function set()
    {
      $this->data['bar']['zoo'] = 'foo2';
      return $this;
    }
  }
}
