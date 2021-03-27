<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\IO\Response;

/**
 * Designed for the Response Object; Adds methods to process headers
 *
 * @vendor   UGComponents
 * @package  Server
 * @standard PSR-2
 */
trait HeaderTrait
{
  /**
   * Adds a header parameter
   *
   * @param *string   $name  Name of the header
   * @param string|null $value Value of the header
   *
   * @return HeaderTrait
   */
  public function addHeader(string $name, string $value = null)
  {
    if (!is_null($value)) {
      return $this->set('headers', $name, $value);
    }

    return $this->set('headers', $name, null);
  }

  /**
   * Returns either the header value given
   * the name or the all headers
   *
   * @param *string $name  Name of the header
   *
   * @return mixed
   */
  public function getHeaders(string $name = null)
  {
    if (is_null($name)) {
      return $this->get('headers');
    }

    return $this->get('headers', $name);
  }

  /**
   * Removes a header parameter
   *
   * @param *string $name Name of the header
   *
   * @return HeaderTrait
   */
  public function removeHeader(string $name)
  {
    return $this->remove('headers', $name);
  }
}
