<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Http;

use Exception;

/**
 * HTTP exceptions
 *
 * @package  PHPUGPH
 * @category Http
 * @standard PSR-2
 */
class HttpException extends Exception
{
  /**
   * @const string ERROR_NOT_FOUND 404 Error template
   */
  const ERROR_NOT_FOUND = '404 Not Found';

  /**
   * Create a new exception for 404
   *
   * @return HttpException
   */
  public static function forResponseNotFound(): HttpException
  {
    return new static(static::ERROR_NOT_FOUND, 404);
  }
}
