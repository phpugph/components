<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\IO\Request;

/**
 * Designed for the Request Object; Adds methods to store raw input
 *
 * @vendor   UGComponents
 * @package  IO
 * @standard PSR-2
 */
trait ContentTrait
{
  /**
   * Returns final input stream
   *
   * @return string|null
   */
  public function getContent()
  {
    return $this->get('body');
  }

  /**
   * Returns true if has content
   *
   * @return bool
   */
  public function hasContent(): bool
  {
    return !$this->isEmpty('body');
  }

  /**
   * Sets content
   *
   * @param *mixed $content
   *
   * @return ContentTrait
   */
  public function setContent($content)
  {
    $this->set('body', $content);
    return $this;
  }
}
