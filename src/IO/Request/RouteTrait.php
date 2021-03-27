<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\IO\Request;

/**
 * Designed for the Request Object; Adds methods to store passed Router data
 *
 * @vendor   UGComponents
 * @package  IO
 * @standard PSR-2
 */
trait RouteTrait
{
  /**
   * Returns route data given name or all route data
   *
   * @param string|null $name The key name in the route
   * @param mixed     ...$args
   *
   * @return mixed
   */
  public function getRoute(string $name = null, ...$args)
  {
    if (is_null($name)) {
      return $this->get('route');
    }

    return $this->get('route', $name, ...$args);
  }

  /**
   * Returns route data given name or all route data
   *
   * @param string|null $name The parameter name
   *
   * @return mixed
   */
  public function getParameters(string $name = null)
  {
    if (is_null($name)) {
      return $this->getRoute('parameters');
    }

    return $this->getRoute('parameters', $name);
  }

  /**
   * Returns route data given name or all route data
   *
   * @param int|null $index The variable index
   *
   * @return mixed
   */
  public function getVariables(int $index = null)
  {
    if (is_null($index)) {
      return $this->getRoute('variables');
    }

    return $this->getRoute('variables', $index);
  }

  /**
   * Sets a request route
   *
   * @param *array $results
   *
   * @return RouteTrait
   */
  public function setRoute(array $route)
  {
    return $this->set('route', $route);
  }
}
