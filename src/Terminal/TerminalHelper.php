<?php //-->
/*
 * This file is part of the UGComponents Command Line.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Terminal;

/**
 * Framework command line
 *
 * @package  PHPUGPH
 * @category Framework
 * @standard PSR-2
 */
class TerminalHelper
{
  /**
   * @var string $stdin
   */
  protected static $stdin = 'php://stdin';

  /**
   * @var Closure|null $map The global print callback
   */
  protected static $map = null;

  /**
   * Outputs colorful (red) message
   *
   * @param *string $message The message
   *
   * @return void
   */
  public static function error($message, $die = true)
  {
    self::output(
      trim($message),
      "\033[31m%s\033[0m"
    );

    self::output(PHP_EOL);

    // @codeCoverageIgnoreStart
    if ($die) {
      self::output(PHP_EOL);
      die(1);
    }
    // @codeCoverageIgnoreEnd
  }

  /**
   * Outputs colorful (blue) message
   *
   * @param *string $message The message
   *
   * @return void
   */
  public static function info($message)
  {
    self::output(
      trim($message),
      "\033[36m%s\033[0m"
    );

    self::output(PHP_EOL);
  }

  /**
   * Queries the user for an
   * input and returns the results
   *
   * @param string    $question The text question
   * @param string|null $default  The default answer
   *
   * @return string
   * @codeCoverageIgnore
   */
  public static function input($question, $default = null)
  {
    self::output($question . ': ');
    $handle = fopen(static::$stdin, 'r');

    $answer = fgets($handle);
    fclose($handle);

    $answer = trim($answer);

    if (!$answer) {
      $answer = $default;
    }

    return $answer;
  }

  /**
   * Outputs message
   *
   * @param *string $message The message
   * @param string  $color   Unix color code
   *
   * @return void
   */
  public static function output($message, $color = null)
  {
    if (is_string($color)) {
      $message = sprintf($color, $message);
    }

    if (is_null(self::$map)) {
      self::setMap();
    }

    call_user_func(self::$map, $message);
  }

  /**
   * PARSE ARGUMENTS
   *
   * This command line option parser supports any combination of three types of options
   * [single character options (`-a -b` or `-ab` or `-c -d=dog` or `-cd dog`),
   * long options (`--foo` or `--bar=baz` or `--bar baz`)
   * and arguments (`arg1 arg2`)] and returns a simple array.
   *
   * [pfisher ~]$ php test.php --foo --bar=baz --spam eggs
   *   ["foo"]   => true
   *   ["bar"]   => "baz"
   *   ["spam"]  => "eggs"
   *
   * [pfisher ~]$ php test.php -abc foo
   *   ["a"]   => true
   *   ["b"]   => true
   *   ["c"]   => "foo"
   *
   * [pfisher ~]$ php test.php arg1 arg2 arg3
   *   [0]     => "arg1"
   *   [1]     => "arg2"
   *   [2]     => "arg3"
   *
   * [pfisher ~]$ php test.php plain-arg --foo --bar=baz --funny="spam=eggs" --also-funny=spam=eggs \
   * > 'plain arg 2' -abc -k=value "plain arg 3" --s="original" --s='overwrite' --s
   *   [0]     => "plain-arg"
   *   ["foo"]   => true
   *   ["bar"]   => "baz"
   *   ["funny"] => "spam=eggs"
   *   ["also-funny"]=> "spam=eggs"
   *   [1]     => "plain arg 2"
   *   ["a"]   => true
   *   ["b"]   => true
   *   ["c"]   => true
   *   ["k"]   => "value"
   *   [2]     => "plain arg 3"
   *   ["s"]   => "overwrite"
   *
   * Not supported: `-cd=dog`.
   *
   * @author        Patrick Fisher <patrick@pwfisher.com>
   * @since         August 21, 2009
   * @see         https://github.com/pwfisher/CommandLine.php
   * @see         http://www.php.net/manual/en/features.commandline.php
   *            #81042 function arguments($args) by technorati at gmail dot com, 12-Feb-2008
   *            #78651 function getArgs($args) by B Crawford, 22-Oct-2007
   * @usage         $args = CommandLine::parseArgs($_SERVER['argv']);
   */
  public static function parseArgs(array $args = null)
  {
    $results = [];
    for ($i = 0, $j = count($args); $i < $j; $i++) {
      $arg = $args[$i];
      // --foo --bar=baz
      if (substr($arg, 0, 2) === '--') {
        $equalPosition = strpos($arg, '=');
        // --foo
        if ($equalPosition === false) {
          $key = substr($arg, 2);
          // --foo value
          if ($i + 1 < $j && $args[$i + 1][0] !== '-') {
            $value = $args[$i + 1];
            $i++;
          } else {
            $value = true;

            if (isset($results[$key])) {
              $value = $results[$key];
            }
          }

          $results[$key] = $value;
        // --bar=baz
        } else {
          $key = substr($arg, 2, $equalPosition - 2);
          $value = substr($arg, $equalPosition + 1);
          $results[$key] = $value;
        }
      // -k=value -abc
      } else if (substr($arg, 0, 1) === '-') {
        // -k=value
        if (substr($arg, 2, 1) === '=') {
          $key = substr($arg, 1, 1);
          $value = substr($arg, 3);
          $results[$key] = $value;
        // -abc
        } else {
          $chars = str_split(substr($arg, 1));
          foreach ($chars as $char) {
            $key = $char;
            $value = isset($results[$key]) ? $results[$key] : true;
            $results[$key] = $value;
          }

          // -a value1 -abc value2
          if ($i + 1 < $j && $args[$i + 1][0] !== '-') {
            $results[$key] = $args[$i + 1];
            $i++;
          }
        }
      } else if (strpos($arg, '=') !== false) {
        $equalPosition = strpos($arg, '=');
        $key = substr($arg, 0, $equalPosition);
        $value = substr($arg, $equalPosition + 1);
        $results[$key] = $value;
      // plain-arg
      } else {
        $value = $arg;
        $results[] = $value;
      }
    }

    if (isset($results['__json'])) {
      $json = $results['__json'];
      unset($results['__json']);

      $results = array_merge(json_decode($json, true), $results);
    }

    if (isset($results['__json64'])) {
      $base64 = $results['__json64'];
      unset($results['__json64']);

      $json = base64_decode($base64);
      $results = array_merge(json_decode($json, true), $results);
    }

    if (isset($results['__query'])) {
      $query = $results['__query'];
      unset($results['__query']);

      parse_str($query, $query);
      $results = array_merge($query, $results);
    }

    return $results;
  }

  /**
   * Setups the output map
   *
   * @param string|null $map
   */
  public static function setMap($map = null)
  {
    if (is_null(self::$map)) {
      self::$map = include(__DIR__ . '/map.php');
    }

    if (!is_null($map)) {
      self::$map = $map;
    }
  }

  /**
   * Outputs colorful (green) message
   *
   * @param *string $message The message
   *
   * @return void
   */
  public static function success($message)
  {
    self::output(
      trim($message),
      "\033[32m%s\033[0m"
    );

    self::output(PHP_EOL);
  }

  /**
   * Outputs colorful (purple) message
   *
   * @param *string $message The message
   *
   * @return void
   */
  public static function system($message)
  {
    self::output(
      trim($message),
      "\033[34m%s\033[0m"
    );

    self::output(PHP_EOL);
  }

  /**
   * Outputs colorful (orange) message
   *
   * @param *string $message The message
   *
   * @return void
   */
  public static function warning($message)
  {
    self::output(
      trim($message),
      "\033[33m%s\033[0m"
    );

    self::output(PHP_EOL);
  }
}
