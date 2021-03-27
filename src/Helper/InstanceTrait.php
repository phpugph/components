<?php
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Helper;

/**
 * Instantiates a class without using `new`. This is used
 * primarly to start chaining immediately without assigning
 * this instance to a variable
 *
 * @package  PHPUGPH
 * @category Helper
 * @standard PSR-2
 */
trait InstanceTrait
{
  /**
   * One of the hard thing about instantiating classes is
   * that design patterns can impose different ways of
   * instantiating a class. The word "new" is not flexible.
   * Authors of classes should be able to control how a class
   * is instantiated, while leaving others using the class
   * oblivious to it. All in all its one less thing to remember
   * for each class call. By default we instantiate classes with
   * this method.
   *
   * @param mixed ...$args Arguments to pass to the constructor
   *
   * @return InstanceTrait
   */
  public static function i(...$args)
  {
    $class = get_called_class();
    return new $class(...$args);
  }
}
