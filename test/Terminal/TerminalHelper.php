<?php

namespace UGComponents\Terminal;

use StdClass;
use PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 13:49:45.
 */
class Terminal_Helper_Test extends TestCase
{
  /**
   * @covers UGComponents\Terminal\TerminalHelper::error
   * @covers UGComponents\Terminal\TerminalHelper::output
   * @covers UGComponents\Terminal\TerminalHelper::setMap
   */
  public function testError()
  {
    $test = new StdClass();
    $test->results = false;
    TerminalHelper::setMap(function ($output, $color) use ($test) {
      if($test->results === false) {
        $test->results = sprintf($color, $output);
      }
    });

    TerminalHelper::error('Something Happened', false);
    $expected = sprintf("\033[31m%s\033[0m", 'Something Happened');
    $this->assertEquals($expected, $test->results);
  }

  /**
   * @covers UGComponents\Terminal\TerminalHelper::info
   * @covers UGComponents\Terminal\TerminalHelper::output
   */
  public function testInfo()
  {
    $test = new StdClass();
    $test->results = false;
    TerminalHelper::setMap(function ($output, $color) use ($test) {
      if($test->results === false) {
        $test->results = sprintf($color, $output);
      }
    });

    TerminalHelper::info('Something Happened', false);
    $expected = sprintf("\033[36m%s\033[0m", 'Something Happened');
    $this->assertEquals($expected, $test->results);
  }

  /**
   * @covers UGComponents\Terminal\TerminalHelper::parseArgs
   */
  public function testParseArgs()
  {
    $actual = TerminalHelper::parseArgs(['--foo', '--bar=baz', '--spam', 'eggs']);

    $this->assertEquals(1, $actual['foo']);
    $this->assertEquals('baz', $actual['bar']);
    $this->assertEquals('eggs', $actual['spam']);

    $actual = TerminalHelper::parseArgs(['-abc', 'foo']);

    $this->assertTrue($actual['a']);
    $this->assertTrue($actual['b']);
    $this->assertEquals('foo', $actual['c']);

    $actual = TerminalHelper::parseArgs(['arg1', 'arg2', 'arg3']);

    $this->assertEquals('arg1', $actual[0]);
    $this->assertEquals('arg2', $actual[1]);
    $this->assertEquals('arg3', $actual[2]);

    $args = 'plain-arg --foo --bar=baz --funny="spam=eggs" --also-funny=spam=eggs \'plain arg 2\' -abc -k=value "plain arg 3" --s="original" --s=\'overwrite\' --s';

    $actual = TerminalHelper::parseArgs(explode(' ', $args));
    $this->assertEquals('plain-arg', $actual[0]);
    $this->assertEquals(true, $actual['foo']);
    $this->assertEquals('baz', $actual['bar']);
    $this->assertEquals('"spam=eggs"', $actual['funny']);
    $this->assertEquals('spam=eggs', $actual['also-funny']);
    $this->assertEquals('\'plain', $actual[1]);
    $this->assertEquals(true, $actual['a']);
    $this->assertEquals(true, $actual['b']);
    $this->assertEquals(true, $actual['c']);
    $this->assertEquals('value', $actual['k']);
    $this->assertEquals('arg', $actual[2]);
    $this->assertEquals('\'overwrite\'', $actual['s']);

    $actual = TerminalHelper::parseArgs(['arg1=1']);
    $this->assertEquals('1', $actual['arg1']);

    $args = '__query=foo=bar';
    $actual = TerminalHelper::parseArgs(explode(' ', $args));
    $this->assertEquals('bar', $actual['foo']);

    $args = '__json={"foo":"bar"}';
    $actual = TerminalHelper::parseArgs(explode(' ', $args));
    $this->assertEquals('bar', $actual['foo']);

    $args = '__json64=\'' . base64_encode('{"foo":"bar"}') . '\'';
    $actual = TerminalHelper::parseArgs(explode(' ', $args));
    $this->assertEquals('bar', $actual['foo']);
  }

  /**
   * @covers UGComponents\Terminal\TerminalHelper::success
   * @covers UGComponents\Terminal\TerminalHelper::output
   */
  public function testSuccess()
  {
    $test = new StdClass();
    $test->results = false;
    TerminalHelper::setMap(function ($output, $color) use ($test) {
      if($test->results === false) {
        $test->results = sprintf($color, $output);
      }
    });

    TerminalHelper::success('Something Happened', false);
    $expected = sprintf("\033[32m%s\033[0m", 'Something Happened');
    $this->assertEquals($expected, $test->results);
  }

  /**
   * @covers UGComponents\Terminal\TerminalHelper::system
   * @covers UGComponents\Terminal\TerminalHelper::output
   */
  public function testSystem()
  {
    $test = new StdClass();
    $test->results = false;
    TerminalHelper::setMap(function ($output, $color) use ($test) {
      if($test->results === false) {
        $test->results = sprintf($color, $output);
      }
    });

    TerminalHelper::system('Something Happened', false);
    $expected = sprintf("\033[34m%s\033[0m", 'Something Happened');
    $this->assertEquals($expected, $test->results);
  }

  /**
   * @covers UGComponents\Terminal\TerminalHelper::warning
   * @covers UGComponents\Terminal\TerminalHelper::output
   */
  public function testWarning()
  {
    $test = new StdClass();
    $test->results = false;
    TerminalHelper::setMap(function ($output, $color) use ($test) {
      if($test->results === false) {
        $test->results = sprintf($color, $output);
      }
    });

    TerminalHelper::warning('Something Happened', false);
    $expected = sprintf("\033[33m%s\033[0m", 'Something Happened');
    $this->assertEquals($expected, $test->results);
  }
}
