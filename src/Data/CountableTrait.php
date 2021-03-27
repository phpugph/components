<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Data;

/**
 * Given that there's $data this will auto setup Countable interface
 *
 * @package  PHPUGPH
 * @category Data
 * @standard PSR-2
 */
trait CountableTrait
{
  /**
   * Returns the data size
   * For Countable interface
   */
  public function count(): int
  {
    return count($this->data);
  }
}
