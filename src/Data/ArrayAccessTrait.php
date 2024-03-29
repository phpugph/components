<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Data;

/**
 * Given that there's $data this will auto setup ArrayAccess
 *
 * @package  PHPUGPH
 * @category Data
 * @standard PSR-2
 */
trait ArrayAccessTrait
{
  /**
   * isset using the ArrayAccess interface
   *
   * @param *scalar|null $offset The key to test if exists
   *
   * @return bool
   */
  public function offsetExists($offset): bool
  {
    return isset($this->data[$offset]);
  }

  /**
   * returns data using the ArrayAccess interface
   *
   * @param *scalar|null $offset The key to get
   *
   * @return mixed
   */
  public function offsetGet($offset): mixed
  {
    return isset($this->data[$offset]) ? $this->data[$offset] : null;
  }

  /**
   * Sets data using the ArrayAccess interface
   *
   * @param *scalar|null $offset The key to set
   * @param mixed    $value  The value the key should be set to
   */
  public function offsetSet($offset, $value): void
  {
    if (is_null($offset)) {
      $this->data[] = $value;
    } else {
      $this->data[$offset] = $value;
    }
  }

  /**
   * unsets using the ArrayAccess interface
   *
   * @param *scalar|null $offset The key to unset
   */
  public function offsetUnset($offset): void
  {
    unset($this->data[$offset]);
  }
}
