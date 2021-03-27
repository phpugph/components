<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Data;

/**
 * Given that there's $data this will
 * auto setup the Iterator interface
 *
 * @package  PHPUGPH
 * @category Data
 * @standard PSR-2
 */
trait IteratorTrait
{
  /**
   * Returns the current item
   * For Iterator interface
   */
  public function current()
  {
    return current($this->data);
  }

  /**
   * Returns th current position
   * For Iterator interface
   */
  public function key()
  {
    return key($this->data);
  }

  /**
   * Increases the position
   * For Iterator interface
   */
  public function next()
  {
    next($this->data);
  }

  /**
   * Rewinds the position
   * For Iterator interface
   */
  public function rewind()
  {
    reset($this->data);
  }

  /**
   * Validates whether if the index is set
   * For Iterator interface
   *
   * @return bool
   */
  public function valid(): bool
  {
    return isset($this->data[$this->key()]);
  }
}
