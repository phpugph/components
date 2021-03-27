<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\I18n;

use Exception;

/**
 * Language exceptions
 *
 * @package  PHPUGPH
 * @category I18n
 * @standard PSR-2
 */
class LanguageException extends Exception
{

  /**
   * @const string ERROR_INVALID_CALLBACK Error template
   */
  const ERROR_FILE_NOT_SET = 'No file was specified';

  /**
   * Create a new exception for file not set
   *
   * @return LanguageException
   */
  public static function forFileNotSet(): LanguageException
  {
    return new static(static::ERROR_FILE_NOT_SET);
  }
}
