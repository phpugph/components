<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Profiler;

/**
 * JavaScript like caller and callee methods
 *
 * @package  PHPUGPH
 * @category Core
 * @standard PSR-2
 */
trait CallerTrait
{
  /**
   * Returns the caller profile
   *
   * return array
   */
  public function getCaller(): array
  {
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
    return array_pop($trace);
  }

  /**
   * Returns the callee profile
   *
   * return array
   */
  public function getCallee(): array
  {
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    return array_pop($trace);
  }
}
