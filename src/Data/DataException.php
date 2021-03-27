<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Data;

use Exception;

/**
 * Data exceptions
 *
 * @package  PHPUGPH
 * @category Data
 * @standard PSR-2
 */
class DataException extends Exception
{
  /**
   * @const string ERROR_METHOD_NOT_FOUND Error template
   */
  const ERROR_METHOD_NOT_FOUND = 'Method %s->%s() not found';

  /**
   * Create a new exception for invalid method
   *
   * @param *string $class
   * @param *string $name
   *
   * @return DataException
   */
  public static function forMethodNotFound(string $class, string $name): DataException
  {
    return new static(sprintf(static::ERROR_METHOD_NOT_FOUND, $class, $name));
  }
}
