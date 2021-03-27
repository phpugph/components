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

use UGComponents\IO\Request\RequestInterface;
use UGComponents\IO\Request\CliTrait;
use UGComponents\IO\Request\ContentTrait;
use UGComponents\IO\Request\CookieTrait;
use UGComponents\IO\Request\FileTrait;
use UGComponents\IO\Request\GetTrait;
use UGComponents\IO\Request\RouteTrait;
use UGComponents\IO\Request\PostTrait;
use UGComponents\IO\Request\ServerTrait;
use UGComponents\IO\Request\SessionTrait;
use UGComponents\IO\Request\StageTrait;

/**
 * IO Request Object
 *
 * @vendor   UGComponents
 * @package  IO
 * @standard PSR-2
 */
class Request extends AbstractIO implements RequestInterface, IOInterface
{
  use CliTrait,
    ContentTrait,
    CookieTrait,
    FileTrait,
    GetTrait,
    PostTrait,
    RouteTrait,
    ServerTrait,
    SessionTrait,
    StageTrait;

  /**
   * Loads default data given by PHP
   *
   * @return IOInterface
   */
  public function load(): IOInterface
  {
    global $argv;

    $this
      ->setArgs($argv)
      ->setContent(file_get_contents('php://input'));

    if (isset($_COOKIE)) {
      $this->setCookies($_COOKIE);
    }

    // @codeCoverageIgnoreStart
    if (isset($_SESSION)) {
      $this->setSession($_SESSION);
    }
    // @codeCoverageIgnoreEnd

    if (isset($_GET)) {
      $this->setGet($_GET)->setStage($_GET);
    }

    if (isset($_POST)) {
      $this->setPost($_POST)->setStage($_POST);
    }

    if (isset($_FILES)) {
      $this->setFiles($_FILES);
    }

    if (isset($_SERVER)) {
      $this->setServer($_SERVER);
      // @codeCoverageIgnoreStart
      if (!$this->isCLI()) {
        $this->setHost('http');
      }
      // @codeCoverageIgnoreEnd
    }

    return $this;
  }
}
