<?php

namespace UGComponents\Resolver;

use PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 02:11:02.
 */
class Resolver_ResolverException_Test extends TestCase
{
  /**
   * @var ResolverException
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp()
  {
    $this->object = new ResolverException;
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown()
  {
  }

  /**
   * @covers UGComponents\Resolver\ResolverException::forClassNotFound
   */
  public function testForClassNotFound()
  {
    $actual = null;

    try {
			throw ResolverException::forClassNotFound('foobar');
		} catch(ResolverException $e) {
			$actual = $e->getMessage();
		}

		$expected = 'Could not find class foobar.';

		$this->assertEquals($expected, $actual);
  }

  /**
   * @covers UGComponents\Resolver\ResolverException::forMethodNotFound
   */
  public function testForMethodNotFound()
  {
    $actual = null;

    try {
			throw ResolverException::forMethodNotFound('foo', 'bar');
		} catch(ResolverException $e) {
			$actual = $e->getMessage();
		}

		$expected = 'Could not find method foo->bar().';

		$this->assertEquals($expected, $actual);
  }

  /**
   * @covers UGComponents\Resolver\ResolverException::forResolverNotFound
   */
  public function testForResolverNotFound()
  {
    $actual = null;

    try {
			throw ResolverException::forResolverNotFound('foo');
		} catch(ResolverException $e) {
			$actual = $e->getMessage();
		}

		$expected = 'Could not find resolver foo.';
		$this->assertEquals($expected, $actual);
  }
}
