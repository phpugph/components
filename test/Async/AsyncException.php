<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Async;

use PHPUnit\Framework\TestCase;

class Async_AsyncException_Test extends TestCase
{
  /**
   * @var HttpException
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp(): void
  {
    $this->object = new AsyncException;
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown(): void
  {
  }

  /**
   * @covers UGComponents\Async\AsyncException::forInvalidCoroutine
   */
  public function testForInvalidCoroutine()
  {
    $message = null;
    try {
      throw AsyncException::forInvalidCoroutine('foo');
    } catch(AsyncException $e) {
      $message = $e->getMessage();
    }

    $this->assertEquals('Argument 1 was expecting either a Generator or callable, string used.', $message);
  }
}
