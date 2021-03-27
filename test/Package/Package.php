<?php

namespace UGComponents\Package;

use PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 13:49:45.
 */
class Package_Package_Test extends TestCase
{
  /**
   * @var Package
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp()
  {
    $this->object = new Package(new PackageHandler, 'foo/bar');
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown()
  {
  }

  /**
   * @covers UGComponents\Package\Package::__call
   */
  public function test__call()
  {
    $this->object->addMethod('foo', function() {
      return 'bar';
    });

    $actual = $this->object->__call('foo', array());
    $this->assertEquals('bar', $actual);

    $trigger = false;
    try {
      $actual = $this->object->__call('bar', array());
    } catch(PackageException $e) {
      $trigger = true;
    }

    $this->assertTrue($trigger);
  }

  /**
   * @covers UGComponents\Package\Package::addMethod
   */
  public function testAddMethod()
  {
    $this->object->addMethod('foo', function() {
      return 'bar';
    });

    $actual = $this->object->__call('foo', array());
    $this->assertEquals('bar', $actual);
  }

  /**
   * @covers UGComponents\Package\Package::__construct
   * @covers UGComponents\Package\Package::getPackagePath
   */
  public function testGetPackagePath()
  {
    //foo/bar
    $actual = $this->object->getPackagePath();
    $this->assertContains('/foo/bar', $actual);

    $this->object->__construct(new PackageHandler, '/foo/bar');
    $actual = $this->object->getPackagePath();
    $this->assertContains('/foo/bar', $actual);
    $this->assertFalse(strpos($actual, '/vendor/foo/bar'));

    $this->object->__construct(new PackageHandler, 'foo');
    $actual = $this->object->getPackagePath();
    $this->assertFalse($actual);
  }

  /**
   * @covers UGComponents\Package\Package::getPackageType
   */
  public function testGetPackageType()
  {
    //foo/bar
    $actual = $this->object->getPackageType();
    $this->assertEquals('vendor', $actual);

    $this->object->__construct(new PackageHandler, '/foo/bar');
    $actual = $this->object->getPackageType();
    $this->assertEquals('root', $actual);

    $this->object->__construct(new PackageHandler, 'foo');
    $actual = $this->object->getPackageType();
    $this->assertEquals('pseudo', $actual);
  }

  /**
   * @covers UGComponents\Package\Package::mapPackageMethods
   * @covers UGComponents\Package\Package::__call
   */
  public function testMapPackageMethods()
  {
    //foo/bar
    $this->object->mapPackageMethods(new \Exception('test'));
    $actual = $this->object->getMessage();
    $this->assertEquals('test', $actual);

    $this->object->addMethod('getMessage', function() {
      return 'override';
    });

    $actual = $this->object->getMessage();
    $this->assertEquals('override', $actual);
  }
}
