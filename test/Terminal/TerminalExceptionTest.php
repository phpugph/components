<?php

namespace UGComponents\Terminal;

use PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-28 at 11:36:33.
 */
class TerminalExceptionTest extends TestCase
{
  /**
   * @var TerminalException
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp(): void
  {
    $this->object = new TerminalException;
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown(): void
  {
  }

  /**
   * @covers UGComponents\Terminal\TerminalException::forArgumentCount
   */
  public function testForArgumentCount()
  {
    $message = null;
    try {
      throw TerminalException::forArgumentCount();
    } catch(TerminalException $e) {
      $message = $e->getMessage();
    }

    $this->assertEquals('Not enough arguments.', $message);
  }

  /**
   * @covers UGComponents\Terminal\TerminalException::forResponseNotFound
   */
  public function testForResponseNotFound()
  {
    $message = null;
    try {
      throw TerminalException::forResponseNotFound();
    } catch(TerminalException $e) {
      $message = $e->getMessage();
    }

    $this->assertEquals('404 Not Found', $message);
  }
}
