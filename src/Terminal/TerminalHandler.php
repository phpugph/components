<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Terminal;

use UGComponents\Helper\InstanceTrait;
use UGComponents\Helper\LoopTrait;
use UGComponents\Helper\ConditionalTrait;

use UGComponents\Profiler\InspectorTrait;
use UGComponents\Profiler\LoggerTrait;

use UGComponents\Resolver\StateTrait;

/**
 * Main HTTP Handler which connects everything together.
 * We moved out everything that is not the main process flow
 * to traits and reinserted them here to make this easy to follow.
 *
 * @vendor   UGComponents
 * @package  Terminal
 * @standard PSR-2
 */
class TerminalHandler
{
  use TerminalTrait,
    InstanceTrait,
    LoopTrait,
    ConditionalTrait,
    InspectorTrait,
    LoggerTrait,
    StateTrait
    {
      StateTrait::__callResolver as __call;
  }

  /**
   * @const STATUS_200 Status template
   */
  const STATUS_200 = '200 OK';

  /**
   * @const STATUS_308 Status template
   */
  const STATUS_308 = '308 Incomplete';

  /**
   * @const STATUS_404 Status template
   */
  const STATUS_404 = '404 Not Found';

  /**
   * @const STATUS_500 Status template
   */
  const STATUS_500 = '500 Server Error';
}
