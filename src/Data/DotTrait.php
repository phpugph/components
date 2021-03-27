<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Data;

/**
 * The DotTrait allows multidimensional $data to
 * be accessed like `foo.bar.zoo` as well as be
 * manipulated in the same fashion.
 *
 * @package  PHPUGPH
 * @category Data
 * @standard PSR-2
 */
trait DotTrait
{
  /**
   * Gets a value given the path in the registry.
   *
   * @param *string $notation  Name space string notation
   * @param string  $separator If you want to specify a different separator other than dot
   *
   * @return mixed
   */
  public function getDot(string $notation, string $separator = '.')
  {
    $args = explode($separator, $notation);

    if (count($args) == 0) {
      return null;
    }

    $last = array_pop($args);
    $pointer = &$this->data;

    foreach ($args as $step) {
      if (!isset($pointer[$step])) {
        return null;
      }

      $pointer = &$pointer[$step];
    }

    if (!isset($pointer[$last])) {
      return null;
    }

    return $pointer[$last];
  }

  /**
   * Checks to see if a key is set
   *
   * @param *string $notation  Name space string notation
   * @param string  $separator If you want to specify a different separator other than dot
   *
   * @return bool
   */
  public function isDot(string $notation, string $separator = '.'): bool
  {
    $args = explode($separator, $notation);

    if (count($args) == 0) {
      return false;
    }

    $last = array_pop($args);

    $pointer = &$this->data;
    foreach ($args as $i => $step) {
      if (!isset($pointer[$step])
        || !is_array($pointer[$step])
      ) {
        return false;
      }

      $pointer = &$pointer[$step];
    }

    if (!isset($pointer[$last])) {
      return false;
    }

    return true;
  }

  /**
   * Removes name space given notation
   *
   * @param *string $notation  Name space string notation
   * @param string  $separator If you want to specify a different separator other than dot
   *
   * @return DotTrait
   */
  public function removeDot(string $notation, string $separator = '.')
  {
    $args = explode($separator, $notation);

    if (count($args) === 0) {
      return $this;
    }

    $last = array_pop($args);

    $pointer = &$this->data;
    foreach ($args as $i => $step) {
      if (!isset($pointer[$step])
        || !is_array($pointer[$step])
      ) {
        $pointer[$step] = [];
      }

      $pointer = &$pointer[$step];
    }

    unset($pointer[$last]);

    return $this;
  }

  /**
   * Creates the name space given the space
   * and sets the value to that name space
   *
   * @param *string $notation  Name space string notation
   * @param *mixed  $value   Value to set on this namespace
   * @param string  $separator If you want to specify a different separator other than dot
   *
   * @return DotTrait
   */
  public function setDot(string $notation, $value, string $separator = '.')
  {
    $args = explode($separator, $notation);

    if (count($args) === 0) {
      return $this;
    }

    $last = array_pop($args);

    $pointer = &$this->data;
    foreach ($args as $i => $step) {
      if (!isset($pointer[$step])
        || !is_array($pointer[$step])
      ) {
        $pointer[$step] = [];
      }

      $pointer = &$pointer[$step];
    }

    $pointer[$last] = $value;

    return $this;
  }
}
