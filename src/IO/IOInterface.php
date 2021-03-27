<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\IO;

/**
 * IO Interface
 *
 * @vendor   UGComponents
 * @package  IO
 * @standard PSR-2
 */
interface IOInterface
{
  /**
   * Loads default data given by PHP
   *
   * @return Request
   */
  public function load(): IOInterface;
}
