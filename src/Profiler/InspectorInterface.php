<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Profiler;

/**
 * Used to inspect classes and result sets
 *
 * @package  PHPUGPH
 * @category Inspector
 * @standard PSR-2
 */
interface InspectorInterface
{
  /**
   * Call a method of the scope and output it
   *
   * @param *string $name the name of the method
   * @param *array  $args arguments that were passed
   *
   * @return mixed
   */
  public function __call(string $name, array $args);

  /**
   * Hijacks the class and reports the results of the next
   * method call
   *
   * @param *object   $scope the class instance
   * @param string|null $name  the name of the property to inspect
   *
   * @return InspectorInterface
   */
  public function next($scope, string $name = null): InspectorInterface;

  /**
   * Outputs anything
   *
   * @param *mixed $variable any data
   *
   * @return InspectorInterface
   */
  public function output($variable): InspectorInterface;
}
