<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Http\Router;

use UGComponents\IO\Request\RequestInterface;

/**
 * Handles method-path matching and routing
 *
 * @vendor   UGComponents
 * @package  Http
 * @standard PSR-2
 */
interface RouterInterface
{

  /**
   * Process routes
   *
   * @return bool
   */
  public function process(RequestInterface $request, ...$args): bool;

  /**
   * Adds routing middleware
   *
   * @param string   $method   The request method
   * @param string   $pattern  The route pattern
   * @param callable $callback The middleware handler
   *
   * @return RouterInterface
   */
  public function route(string $method, string $pattern, callable $callback): RouterInterface;
}
