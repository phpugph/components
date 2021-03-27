<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\IO\Middleware;

/**
 * Express style middleware object
 *
 * @vendor   UGComponents
 * @package  IO
 * @standard PSR-2
 */
interface MiddlewareInterface
{
  /**
   * Adds global middleware
   *
   * @param callable $callback The middleware handler
   *
   * @return MiddlewareInterface
   */
  public function register(callable $callback): MiddlewareInterface;

  /**
   * Process middleware
   *
   * @return bool
   */
  public function process(...$args): bool;
}
