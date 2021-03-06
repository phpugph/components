<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\IO;

use UGComponents\Helper\InstanceTrait;
use UGComponents\Helper\LoopTrait;
use UGComponents\Helper\ConditionalTrait;

use UGComponents\Profiler\InspectorTrait;
use UGComponents\Profiler\LoggerTrait;

use UGComponents\Resolver\StateTrait;

use UGComponents\IO\Request\CookieTrait;
use UGComponents\IO\Request\SessionTrait;

use UGComponents\IO\Response\ResponseInterface;
use UGComponents\IO\Response\ContentTrait;
use UGComponents\IO\Response\HeaderTrait;
use UGComponents\IO\Response\RestTrait;
use UGComponents\IO\Response\StatusTrait;

/**
 * IO Response Object
 *
 * @vendor   UGComponents
 * @package  Server
 * @standard PSR-2
 */
class Response extends AbstractIO implements ResponseInterface, IOInterface
{
  use ContentTrait,
    HeaderTrait,
    RestTrait,
    CookieTrait,
    SessionTrait,
    StatusTrait;

  /**
   * Loads default data
   *
   * @return Response
   */
  public function load(): IOInterface
  {
    if (isset($_COOKIE)) {
      $this->setCookies($_COOKIE);
    }

    // @codeCoverageIgnoreStart
    if (isset($_SESSION)) {
      $this->setSession($_SESSION);
    }
    // @codeCoverageIgnoreEnd

    $this
      ->addHeader('Content-Type', 'text/html; charset=utf-8')
      ->setStatus(200, '200 OK');

    return $this;
  }
}
