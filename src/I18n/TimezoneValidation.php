<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\I18n;

use DateTimeZone;

/**
 * Timezone Validation Class
 *
 * @package  PHPUGPH
 * @category I18n
 * @standard PSR-2
 */
class TimezoneValidation
{
  /**
   * Validates that value is a proper abbreviation
   *
   * @param *scalar $value The value to test against
   *
   * @return bool
   */
  public static function isAbbr($value): bool
  {
    return preg_match('/^[A-Z]{1,5}$/', $value);
  }

  /**
   * Validates that value is a proper location
   *
   * @param *scalar $value The value to test against
   *
   * @return bool
   */
  public static function isLocation($value): bool
  {
    return in_array($value, DateTimeZone::listIdentifiers());
  }

  /**
   * Validates that value is a proper UTC
   *
   * @param *scalar $value The value to test against
   *
   * @return bool
   */
  public static function isUtc($value): bool
  {
    return preg_match('/^(GMT|UTC){0,1}(\-|\+)[0-9]{1,2}(\:{0,1}[0-9]{2}){0,1}$/', $value);
  }
}
