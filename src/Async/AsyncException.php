<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Async;

use Exception;

/**
 * Async exceptions
 *
 * @package  PHPUGPH
 * @category Async
 * @standard PSR-2
 */
class AsyncException extends Exception
{
  /**
   * @const string ERROR_INVALID_COROUTINE Error template
   */
  const ERROR_INVALID_COROUTINE = 'Argument 1 was expecting either a Generator or callable, %s used.';

  /**
   * Create a new exception for invalid task
   *
   * @return AsyncException
   */
  public static function forInvalidCoroutine($value): AsyncException
  {
    return new static(sprintf(static::ERROR_INVALID_COROUTINE, gettype($value)));
  }
}
