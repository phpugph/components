<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\IO\Middleware;

use Closure;
use UGComponents\IO\Middleware;

/**
 * These sets of callbacks are called after the connection is closed
 *
 * @package  PHPUGPH
 * @category IO
 * @standard PSR-2
 */
trait PostProcessorTrait
{
  /**
   * @var Middleware|null $preProcessor
   */
  protected $postProcessor = null;

  /**
   * Returns a middleware object
   *
   * @return MiddlewareInterface
   */
  public function getPostprocessor(): MiddlewareInterface
  {
    if (is_null($this->postProcessor)) {
      if (method_exists($this, 'resolve')) {
        $this->setPostprocessor($this->resolve(Middleware::class));
      } else {
        $this->setPostprocessor(new Middleware());
      }
    }

    return $this->postProcessor;
  }

  /**
   * Adds middleware
   *
   * @param *callable $callback The middleware handler
   *
   * @return PostProcessorTrait
   */
  public function postprocess(callable $callback)
  {
    if ($callback instanceof Closure) {
      $callback = $callback->bindTo($this, get_class($this));
    }

    $this->getPostprocessor()->register($callback);
    return $this;
  }

  /**
   * Sets the middleware to use
   *
   * @param *MiddlewareInterface $middleare
   *
   * @return PostProcessorTrait
   */
  public function setPostprocessor(MiddlewareInterface $middleware)
  {
    $this->postProcessor = $middleware;

    return $this;
  }
}
