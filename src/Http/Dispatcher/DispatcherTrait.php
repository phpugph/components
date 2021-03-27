<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Http\Dispatcher;

use UGComponents\Http\HttpDispatcher;

/**
 * Designed for the HttpHandler we are parting this out
 * to lessen the confusion
 *
 * @package  PHPUGPH
 * @category Http
 * @standard PSR-2
 */
trait DispatcherTrait
{
  /**
   * @var HttpDispatcher|null $dispatcher Response object to use
   */
  protected $dispatcher = null;

  /**
   * Returns a response object
   *
   * @return DispatcherInterface
   */
  public function getDispatcher(): DispatcherInterface
  {
    if (is_null($this->dispatcher)) {
      $this->setDispatcher($this->resolve(HttpDispatcher::class));
    }

    return $this->dispatcher;
  }

  /**
   * Sets the dispatcher to use
   *
   * @param DispatcherInterface $dispatcher
   *
   * @return DispatcherTrait
   */
  public function setDispatcher(DispatcherInterface $dispatcher)
  {
    $this->dispatcher = $dispatcher;

    return $this;
  }
}
