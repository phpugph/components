<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Async;

/**
 * A promise represents the eventual result of an asynchronous operation.
 *
 * @package  PHPUGPH
 * @category Async
 * @standard PSR-2
 */
interface PromiseInterface
{
  /**
   * Appends fulfillment and rejection handlers to the promise, and returns
   * a new promise resolving to the return value of the called handler.
   *
   * @param callable $onFulfilled
   * @param callable $onRejected
   *
   * @return PromiseInterface
   */
  public function then(
    callable $onFulfilled = null,
    callable $onRejected = null
  ): PromiseInterface;
}
