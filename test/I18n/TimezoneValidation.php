<?php

namespace UGComponents\I18n;

use PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 02:11:01.
 */
class I18n_TimezoneValidation_Test extends TestCase
{
  /**
   * @var TimezoneValidation
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp()
  {
    $this->object = new TimezoneValidation;
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown()
  {
  }

  /**
   * @covers UGComponents\I18n\TimezoneValidation::isAbbr
   */
  public function testIsAbbr()
  {
    $this->assertTrue((bool) $this->object->isAbbr('ABCDE'));
    $this->assertFalse((bool) $this->object->isAbbr('abcde'));
  }

  /**
   * @covers UGComponents\I18n\TimezoneValidation::isLocation
   */
  public function testIsLocation()
  {
    $this->assertTrue((bool) $this->object->isLocation('Asia/Manila'));
  }

  /**
   * @covers UGComponents\I18n\TimezoneValidation::isUtc
   */
  public function testIsUtc()
  {
    $this->assertTrue((bool) $this->object->isUtc('GMT+8'));   
  }
}
