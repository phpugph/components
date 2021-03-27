<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Helper;

use Closure;

/**
 * Adds a method to bind callables to the current instance
 *
 * @package  PHPUGPH
 * @category Helper
 * @standard PSR-2
 */
trait BinderTrait
{
  /**
   * Binds callback with this instance
   *
   * @param *Closure $conditional should evaluate to true
   *
   * @return Closure
   */
  public function bindCallback(Closure $callback): Closure
  {
    return $callback->bindTo($this, get_class($this));
  }
}
