<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Async;

use Generator;
use Closure;
use ReflectionFunction;

/**
 * Asyncronous Task Runner
 *
 * @package  PHPUGPH
 * @category Async
 * @standard PSR-2
 */
class Coroutine
{
  /**
   * @var callable|null $callback
   */
  protected $callback = null;

  /**
   * @var Generator|null $routine
   */
  protected $routine = null;

  /**
   * @var mixed $value
   */
  protected $value = null;

  /**
   * Sets the callback for the routine
   *
   * @param Generator|callable $callback
   */
  public function __construct($callback)
  {
    //argument test
    if (!is_callable($callback) && !($callback instanceof Generator)) {
      throw AsyncException::forInvalidCoroutine($callback);
    }

    $this->callback = $callback;
  }

  /**
   * Returns the task ID
   *
   * @return string
   */
  public function getId(): string
  {
    $callback = $this->callback;
    if (is_array($callback)) {
      if (isset($callback[0]) && is_object($callback[0])) {
        $callback[0] = spl_object_hash($callback[0]);
      }

      return $callback[0] . '::' . $callback[1];
    }

    if ($callback instanceof Closure || $callback instanceof Generator) {
      return spl_object_hash($callback);
    }

    return $callback;
  }

  /**
   * Returns the current value
   *
   * @return mixed
   */
  public function getValue()
  {
    return $this->value;
  }

  /**
   * Returns true if the routine is finished
   *
   * @return bool
   */
  public function isFinished(): bool
  {
    return $this->routine && !$this->routine->valid();
  }

  /**
   * Returns true if the routine is started already
   *
   * @return bool
   */
  public function isStarted(): bool
  {
    return !!$this->routine;
  }

  /**
   * Makes the Routine and returns it
   *
   * @params mixed ...$args
   *
   * @return Generator
   */
  protected function makeRoutine(...$args): Generator
  {
    $callback = $this->callback;

    //if this callback produces a generator class
    $reflection = new ReflectionFunction($callback);
    if ($reflection->isGenerator()) {
      $callback = $callback($this);
    }

    //if the callback is a generator
    if ($callback instanceof Generator) {
      $this->routine = $callback;
      return $this->routine;
    }

    $this->routine = (function () use ($callback, &$args) {
      yield call_user_func_array($callback, $args);
    })();

    return $this->routine;
  }

  /**
   * Resets the task process
   *
   * @return Task
   */
  public function reset(): Coroutine
  {
    $this->routine = null;
    return $this;
  }

  /**
   * Runs the task
   *
   * @params mixed ...$args
   *
   * @return mixed
   */
  public function run(...$args)
  {
    if (!$this->isStarted()) {
      $this->value = $this->makeRoutine(...$args)->current();
    } else {
      $this->value = $this->step();
    }

    return $this->value;
  }

  /**
   * Runs the task. If it's started already, then steps to the next point
   *
   * @return mixed
   */
  protected function step()
  {
    return $this->routine->send($this);
  }
}
