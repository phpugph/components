<?php

namespace UGComponents\Package;

use StdClass;
use UGComponents\Resolver\ResolverTrait;
use PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 13:49:45.
 */
class PackageTraitTest extends TestCase
{
  /**
   * @var PackageTrait
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp(): void
  {
    $this->object = new PackageTraitStub;
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown(): void
  {
  }

  /**
   * @covers UGComponents\Package\PackageTrait::getPackages
   */
  public function testGetPackages()
  {
    $this->object->register('foobar');
    $this->assertTrue(is_array($this->object->getPackages()));
    $this->assertInstanceOf(Package::class, $this->object->getPackages('foobar'));
  }

  /**
   * covers UGComponents\Package\PackageTrait::isPackage
   */
  public function testIsPackage()
  {
    $actual = $this->object->isPackage('foobar');
    $this->assertFalse($actual);

    $actual = $this->object->register('foobar')->isPackage('foobar');
    $this->assertTrue($actual);
  }

  /**
   * covers UGComponents\Package\PackageTrait::package
   * @covers UGComponents\Package\PackageTrait::__invokePackage
   */
  public function testPackage()
  {
    $instance = $this->object->register('foobar')->package('foobar');
    $this->assertInstanceOf(Package::class, $instance);

    $trigger = false;
    try {
      $this->object->package('barfoo222');
    } catch(PackageException $e) {
      $trigger = true;
    }

    $this->assertTrue($trigger);

    $instance = $this->object->__invokePackage('foobar');
    $this->assertInstanceOf(Package::class, $instance);

    $instance = ($this->object)('foobar');
    $this->assertInstanceOf(Package::class, $instance);
  }

  /**
   * covers UGComponents\Package\PackageTrait::register
   * covers UGComponents\Package\PackageTrait::package
   */
  public function testRegister()
  {
    //pseudo
    $instance = $this->object->register('foobar')->package('foobar');
    $this->assertInstanceOf(Package::class, $instance);

    //root
    $instance = $this->object->register('/foo/bar')->package('/foo/bar');
    $this->assertInstanceOf(Package::class, $instance);

    //vendor
    $instance = $this->object->register('foo/bar')->package('foo/bar');
    $this->assertInstanceOf(Package::class, $instance);
  }
}

if(!class_exists('UGComponents\Frame\PackageTraitStub')) {
  class PackageTraitStub extends PackageHandler
  {
    use ResolverTrait;
  }
}
