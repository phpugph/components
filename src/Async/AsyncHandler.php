<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Async;

use SplQueue;
use Generator;
use Exception;

/**
 * Asyncronous Handler
 *
 * @package  PHPUGPH
 * @category Async
 * @standard PSR-2
 */
class AsyncHandler implements QueueInterface
{
  /**
   * @var SplQueue|null $routines
   */
  protected $routines = null;

  /**
   * Sets the queue spool
   *
   * @param string|callable $channelMap
   */
  public function __construct($channelMap = null)
  {
    $this->routines = new SplQueue();

    //get and call the channel map
    $this->getChannelMap($channelMap)($this);
  }

  /**
   * Adds a task
   *
   * @param *Task|Generator|callable $coroutine
   *
   * @return string the routine id
   */
  public function add($routine): Coroutine
  {
    if (!($routine instanceof Coroutine)) {
      $routine = new Coroutine($routine);
    }

    $this->routines->enqueue($routine);

    return $routine;
  }

  /**
   * Kills a routine
   *
   * @param string $id
   *
   * @return bool
   */
  public function kill(string $id): AsyncHandler
  {
    foreach ($this->routines as $i => $routine) {
      if ($routine->getId() === $id) {
        unset($this->routines[$i]);
        break;
      }
    }

    return $this;
  }

  /**
   * Runs all the tasks in the queue, considering steps
   *
   * @param callable $step
   *
   * @return QueueInterface
   */
  public function run(callable $step = null): QueueInterface
  {
    if (is_null($step)) {
      $step = function () {
      };
    }

    while (!$this->routines->isEmpty()) {
      $routine = $this->routines->dequeue();
      $value = $routine->run();

      if (!$routine->isFinished()) {
        call_user_func($step, $value, $routine);
        $this->routines->enqueue($routine);
      }
    }

    return $this;
  }

  /**
   * Determines the chennel map to use
   *
   * @param string|callable $channelMap
   *
   * @return callable
   */
  protected function getChannelMap($channelMap): callable
  {
    //if it's already callable
    if (is_callable($channelMap)) {
      return $channelMap;
    }

    //if the string is a precoded mapper
    if (is_string($channelMap)) {
      $file = sprintf('%s/map/%s.php', __DIR__, $channelMap);
      if (file_exists($file)) {
        return include $file;
      }
    }

    //default choose shutdown
    return include sprintf('%s/map/shutdown.php', __DIR__);
    //we need a way to test for a long running process like when in a while(true)
    //if we can do this, we should switch to socket.php
  }
}
