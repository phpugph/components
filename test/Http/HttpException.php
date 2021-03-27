<?php

namespace UGComponents\Http;

use PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-28 at 11:36:33.
 */
class Http_HttpException_Test extends TestCase
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
    $this->object = new HttpException;
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown(): void
  {
  }

  /**
   * @covers UGComponents\Http\HttpException::forResponseNotFound
   */
  public function testForResponseNotFound()
  {
    $message = null;
    try {
      throw HttpException::forResponseNotFound();
    } catch(HttpException $e) {
      $message = $e->getMessage();
    }

    $this->assertEquals('404 Not Found', $message);
  }
}
