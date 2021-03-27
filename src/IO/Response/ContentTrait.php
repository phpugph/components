<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\IO\Response;

/**
 * Designed for the Response Object; Adds methods to process raw content
 *
 * @vendor   UGComponents
 * @package  Server
 * @standard PSR-2
 */
trait ContentTrait
{
  /**
   * Returns the content body
   *
   * @return mixed
   */
  public function getContent()
  {
    return $this->get('body');
  }

  /**
   * Returns true if content is set
   *
   * @return bool
   */
  public function hasContent(): bool
  {
    $body = $this->get('body');
    return !is_null($body) && strlen((string) $body);
  }

  /**
   * Sets the content
   *
   * @param *mixed $content Can it be an array or string please?
   *
   * @return ResponseInterface
   */
  public function setContent($content)
  {
    if (!is_scalar($content)) {
      $content = json_encode($content, JSON_PARTIAL_OUTPUT_ON_ERROR|JSON_PRETTY_PRINT);
    }

    if (is_bool($content)) {
      $content = $content ? '1': '0';
    }

    if (is_null($content)) {
      $content = '';
    }

    return $this->set('body', $content);
  }
}
