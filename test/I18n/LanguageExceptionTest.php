<?php

namespace UGComponents\I18n;

use PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 02:11:01.
 */
class LanguageExceptionTest extends TestCase
{
  /**
   * @var LanguageException
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp(): void
  {
    $this->object = new LanguageException;
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown(): void
  {
  }

  /**
   * @covers UGComponents\I18n\LanguageException::forFileNotSet
   */
  public function testForFileNotSet()
  {
    $actual = null;
		
    try {
			throw LanguageException::forFileNotSet();
		} catch(LanguageException $e) {
			$actual = $e->getMessage();
		}
		
		$expected = 'No file was specified';
		
		$this->assertEquals($expected, $actual);
  }
}
