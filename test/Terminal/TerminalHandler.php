<?php

namespace UGComponents\Terminal;

use StdClass;
use PHPUnit\Framework\TestCase;
use UGComponents\Terminal\TerminalDispatcher;
use UGComponents\Terminal\Request;
use UGComponents\Terminal\Response;
use UGComponents\Terminal\Router;
use UGComponents\Terminal\Middleware;

use UGComponents\Resolver\ResolverHandler;
use UGComponents\Event\EventEmitter;
use UGComponents\Profiler\InspectorHandler;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-28 at 11:36:33.
 */
class Terminal_TerminalHandler_Test extends TestCase
{
  /**
   * @var TerminalHandler
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp(): void
  {
    $this->object = new TerminalHandler;
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown(): void
  {
  }

  /**
   * @covers UGComponents\Terminal\TerminalTrait::process
   */
  public function testProcess()
  {
    $this->assertTrue($this->object->process());
  }

  /**
   * @covers UGComponents\Terminal\TerminalTrait::run
   * @covers UGComponents\Terminal\TerminalTrait::main
   */
  public function testRun()
  {
    $this->object->getRequest()->setArgs(['bin', 'test', 'foo=bar']);
    $actual = $this->object->run(true);
    $this->assertFalse($actual);

    $this->object->on('test', function ($request, $response) {
      $response->setError(true, 'not sure');
    });

    $actual = $this->object->run(true);
    $this->assertFalse($actual);

    $this->object = new TerminalHandler;

    $this->object->preprocess(function() {
      throw new \Exception('Foobar Exception');
    });

    $this->object->error(function($request, $response) {
      $response->setContent('Foobar Message');
      return false;
    });

    $actual = $this->object->run(true);
    $this->assertFalse($actual);

    $this->object = new TerminalHandler;
    $this->object->getRequest()->setArgs(['bin', 'test', 'foo=bar']);
    $this->object->on('test', function ($request, $response) {});

    $actual = $this->object->run(true);
    $this->assertFalse($actual);

    $this->object = new TerminalHandler;
    $this->object->getRequest()->setArgs(['bin', 'test', 'foo=bar']);
    $this->object->on('test', function ($request, $response) {
      return false;
    });

    $actual = $this->object->run(true);
    $this->assertFalse($actual);

    $this->object = new TerminalHandler;
    $this->object->getRequest()->setArgs(['bin', 'test', 'foo=bar']);

    $actual = $this->object->run(true);
    $this->assertFalse($actual);

    $this->object = new TerminalHandler;
    $this->object->getRequest()->setArgs(['bin']);

    try {
      $this->object->run(true);
    } catch (TerminalException $e) {
      $this->assertEquals('Not enough arguments.', $e->getMessage());
    }
  }
}
