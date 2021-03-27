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
 * These sets of callbacks are called before routing
 *
 * @package  PHPUGPH
 * @category IO
 * @standard PSR-2
 */
trait PreProcessorTrait
{
  /**
   * @var Middleware|null $preProcessor
   */
  protected $preProcessor = null;

  /**
   * Returns a middleware object
   *
   * @return MiddlewareInterface
   */
  public function getPreprocessor(): MiddlewareInterface
  {
    if (is_null($this->preProcessor)) {
      if (method_exists($this, 'resolve')) {
        $this->setPreprocessor($this->resolve(Middleware::class));
      } else {
        $this->setPreprocessor(new Middleware());
      }
    }

    return $this->preProcessor;
  }

  /**
   * Adds middleware
   *
   * @param *callable $callback The middleware handler
   *
   * @return PreProcessorTrait
   */
  public function preprocess(callable $callback)
  {
    if ($callback instanceof Closure) {
      $callback = $callback->bindTo($this, get_class($this));
    }

    $this->getPreprocessor()->register($callback);
    return $this;
  }

  /**
   * Sets the middleware to use
   *
   * @param *MiddlewareInterface $middleare
   *
   * @return PreProcessorTrait
   */
  public function setPreprocessor(MiddlewareInterface $middleware)
  {
    $this->preProcessor = $middleware;

    return $this;
  }
}
