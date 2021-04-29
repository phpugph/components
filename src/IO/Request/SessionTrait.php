<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\IO\Request;

/**
 * Designed for the Request Object; Adds methods to store $_SESSION data
 *
 * @vendor   UGComponents
 * @package  IO
 * @standard PSR-2
 */
trait SessionTrait
{
  /**
   * Returns $_SESSION given name or all $_SESSION
   *
   * @param mixed ...$args
   *
   * @return mixed
   */
  public function getSession(...$args)
  {
    return $this->get('session', ...$args);
  }

  /**
   * Removes $_SESSION given name or all $_SESSION
   *
   * @param mixed ...$args
   *
   * @return SessionTrait
   */
  public function referenceSession(&$session)
  {
    $this->data['session'] = &$session;
    return $this;
  }

  /**
   * Removes $_SESSION given name or all $_SESSION
   *
   * @param mixed ...$args
   *
   * @return SessionTrait
   */
  public function removeSession(...$args)
  {
    // @codeCoverageIgnoreStart
    if (empty($args) && isset($_SESSION)) {
      $_SESSION = [];
      return $this->remove('session');
    }
    // @codeCoverageIgnoreEnd

    return $this->remove('session', ...$args);
  }

  /**
   * Returns true if has $_SESSION given name or if $_SESSION is set
   *
   * @param mixed ...$args
   *
   * @return bool
   */
  public function hasSession(...$args): bool
  {
    return $this->exists('session', ...$args);
  }

  /**
   * Sets $_SESSION
   *
   * @param *mixed $data
   * @param mixed  ...$args
   *
   * @return SessionTrait
   */
  public function setSession($data, ...$args)
  {
    if (is_array($data)) {
      // @codeCoverageIgnoreStart
      if (isset($_SESSION) && $data === $_SESSION) {
        return $this->referenceSession($_SESSION);
      }
      // @codeCoverageIgnoreEnd

      //dont hard set session or we will lose the reference
      foreach ($data as $key => $value) {
        $this->set('session', $key, $value);
      }

      return $this;
    }

    if (count($args) === 0) {
      return $this;
    }

    $this->set('session', $data, ...$args);

    // @codeCoverageIgnoreStart
    if (isset($_SESSION) && $data !== $_SESSION) {
      $_SESSION = $this->get('session');
    }
    // @codeCoverageIgnoreEnd

    return $this;
  }
}
