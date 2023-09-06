<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\IO;

use UGComponents\Data\Registry;

/**
 * IO Request Object
 *
 * @vendor   UGComponents
 * @package  IO
 * @standard PSR-2
 */
abstract class AbstractIO extends Registry
{
  /**
   * Use the default __get functionality
   *
   * @param *string $name  The name of the supposed property
   * @param *mixed  $value The value of the supposed property
   */
  public function __get(string $name)
  {
    if (isset($this[$name])) {
      return $this[$name];
    }

    return null;
  }

  /**
   * Use the default __set functionality
   *
   * @param *string $name  The name of the supposed property
   * @param *mixed  $value The value of the supposed property
   */
  public function __set(string $name, $value)
  {
    $this[$name] = $value;
  }

  /**
   * Loads default data given by PHP
   *
   * @return Request
   */
  abstract public function load(): IOInterface;
}
