<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Terminal;

use Exception;

/**
 * Terminal exceptions
 *
 * @package  PHPUGPH
 * @category Terminal
 * @standard PSR-2
 */
class TerminalException extends Exception
{
  /**
   * @const string ERROR_ARGUMENT_COUNT
   */
  const ERROR_ARGUMENT_COUNT = 'Not enough arguments.';

  /**
   * @const string ERROR_NOT_FOUND 404 Error template
   */
  const ERROR_NOT_FOUND = '404 Not Found';

  /**
   * Create a new exception for 404
   *
   * @return TerminalException
   */
  public static function forArgumentCount(): TerminalException
  {
    return new static(static::ERROR_ARGUMENT_COUNT);
  }

  /**
   * Create a new exception for 404
   *
   * @return TerminalException
   */
  public static function forResponseNotFound(): TerminalException
  {
    return new static(static::ERROR_NOT_FOUND, 404);
  }
}
