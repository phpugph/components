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
use Throwable;
use Closure;

use UGComponents\Helper\InstanceTrait;
use UGComponents\Helper\BinderTrait;
use UGComponents\Resolver\ResolverTrait;

/**
 * A+ Promise implementation
 * see: https://promisesaplus.com/
 *
 * @package  PHPUGPH
 * @category Async
 * @standard PSR-2
 */
class Promise implements PromiseInterface
{
  use ResolverTrait,
    InstanceTrait
    {
      ResolverTrait::resolve as _resolve;
  }

  /**
   * @const int STATUS_FULFILLED
   */
  const STATUS_FULFILLED = 1;

  /**
   * @const int STATUS_PENDING
   */
  const STATUS_PENDING = 0;

  /**
   * @const int STATUS_REJECTED
   */
  const STATUS_REJECTED = 2;

  /**
   * @var AsyncHandler|null $handler
   */
  protected $handler = null;

  /**
   * @var SplQueue|null $queue
   */
  protected $queue = null;

  /**
   * @var stirng $state
   */
  protected $state = self::STATUS_PENDING;

  /**
   * @var mixed $value
   */
  protected $value = null;

  /**
   * Sets up the queue and handler
   *
   * @param *callable     $executor
   * @param AsyncHandler|null $handler
   */
  public function __construct(callable $executor, QueueInterface $handler = null)
  {
    //set the queue
    $this->queue = new SplQueue();

    //determine the handler
    // @codeCoverageIgnoreStart
    if (is_null($handler)) {
      $handler = $this->_resolve(AsyncHandler::class);
    }
    // @codeCoverageIgnoreEnd

    //the promise specs says that the executor should be called immediately
    $handler->add($this->getCoroutine($executor));

    $this->handler = $handler;
  }

  /**
   * Returns a single Promise that resolves when all of the promises
   * passed as an iterable have resolved or when the iterable contains
   * no promises. It rejects with the reason of the first promise that r
   * ejects.
   *
   * See: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Promise/all
   *
   * @param *array      $promises
   * @param AsyncHandler|null $handler
   */
  public static function all(array $promises, QueueInterface $handler = null)
  {
    $executor = function ($fulfill, $reject) use (&$promises) {
      $rejected = false;
      $values = [];

      //loop promises
      foreach ($promises as $i => $promise) {
        //if its not a promise
        if (!($promise instanceof Promise)) {
          //just add to values
          $values[$i] = $promise;
          continue;
        }

        //fulfill
        $promise->then(function ($value) use (
          $fulfill,
          $i,
          &$promises,
          &$values,
          &$rejected
        ) {
          //if rejected
          if ($rejected) {
            //do nothing else
            return;
          }

          //add to the values
          $values[$i] = $value;

          //if the values are not the same length as the promises
          if (count($values) < count($promises)) {
            //do nothing else
            return;
          }

          //we need to sort the values
          krsort($values);
          //now fulfill
          $fulfill($values);
        });

        //catch any errors
        $promise->catch(function ($value) use ($reject, &$rejected) {
          //if rejected
          if ($rejected) {
            //do nothing else
            return;
          }

          $rejected = true;
          $reject($value);
        });
      }

      //if the values are not the same length as the promises
      if (count($values) === count($promises)) {
        //we need to sort the value
        krsort($values);
        //now fulfill
        $fulfill($values);
      }
    };

    return new static($executor, $handler);
  }

  /**
   * Set callbacks to process error catch
   *
   * See: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Promise/catch
   *
   * @param *callable $onRejected
   *
   * @return PromiseInterface
   */
  public function catch(callable $onRejected): PromiseInterface
  {
    return $this->then(null, $onRejected);
  }

  /**
   * Set callbacks to process error catch
   *
   * See: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Promise/finally
   *
   * @param *callable $onFinally
   *
   * @return PromiseInterface
   */
  public function finally(callable $onFinally): PromiseInterface
  {
    $this->queue->enqueue([$onFinally, $onFinally, true]);

    if ($this->state !== static::STATUS_PENDING) {
      $onFinally();
    }

    return $this;
  }

  /**
   * Returns a callback used when as a coroutine for the handler
   *
   * @param callable $routine
   *
   * @return Closure
   */
  protected function getCoroutine(callable $callback): Coroutine
  {
    //set the callback
    $coroutine = function ($routine) use (&$callback) {
      //make the default callbacks
      $fulfill = function ($value) {
        if ($this->state === static::STATUS_PENDING) {
          //set the value
          $this->value = $value;

          //NOTE: it is possible that the settlement is
          //still in progress in these cases the state will
          //automatically update

          //settle the promise
          $this->settle(static::STATUS_FULFILLED);
        }
      };

      $reject = function ($value) {
        if ($this->state === static::STATUS_PENDING) {
          //set the value
          $this->value = $value;

          //NOTE: it is possible that the settlement is
          //still in progress in these cases the state will
          //automatically update

          //settle the promise
          $this->settle(static::STATUS_REJECTED);
        }
      };

      //call the callback
      try {
        $results = $callback($fulfill, $reject, $routine);
      } catch (Throwable $e) {
        yield $reject($e->getMessage());
        return;
      }

      //if this callback is not a generator class
      if (!($results instanceof Generator)) {
        yield $results;
        return;
      }

      try {
        //tada, we have a generator
        foreach ($results as $result) {
          yield $results->send($routine);
        }
      } catch (Throwable $e) {
        yield $reject($e->getMessage());
      }
    };

    return new Coroutine($coroutine);
  }

  /**
   * Returns the current state or compares against what is asserted
   *
   * @param int $assert
   *
   * @return int|bool
   */
  public function getState(int $assert = null)
  {
    if (!is_null($assert)) {
      return $this->state === $assert;
    }

    return $this->state;
  }

  /**
   * Returns a promise that resolves or rejects as soon as
   * one of the promises in an iterable resolves or rejects,
   * with the value or reason from that promise.
   *
   * See: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Promise/race
   *
   * @param *array      $promises
   * @param AsyncHandler|null $handler
   */
  public static function race(array $promises, QueueInterface $handler = null)
  {
    $executor = function ($fulfill, $reject) use (&$value, &$promises) {
      $found = false;

      //loop promises
      foreach ($promises as $i => $promise) {
        //if its not a promise
        if (!($promise instanceof Promise)) {
          //we have our winner
          return $fulfill($promise);
        }

        //fulfill
        $promise->then(function ($value) use ($fulfill, &$found) {
          //if found
          if ($found) {
            //do nothing else
            return;
          }

          $found = true;
          $fulfill($value);
        });

        //catch any errors
        $promise->catch(function ($value) use ($reject, &$found) {
          //if found
          if ($found) {
            //do nothing else
            return;
          }

          $found = true;
          $reject($value);
        });
      }
    };

    return new static($executor, $handler);
  }

  /**
   * Quickly sets up a promise reject given the value to fulfill
   * rejects.
   *
   * See: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Promise/reject
   *
   * @param mixed       $value
   * @param AsyncHandler|null $handler
   */
  public static function reject($value = null, QueueInterface $handler = null)
  {
    return new static(
      function ($fulfill, $reject) use (&$value) {
        $reject($value);
      },
      $handler
    );
  }

  /**
   * Quickly sets up a promise given the value to fulfill
   *
   * See: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Promise/resolve
   *
   * @param mixed       $value
   * @param AsyncHandler|null $handler
   */
  public static function resolve($value = null, QueueInterface $handler = null)
  {
    return new static(
      function ($fulfill) use (&$value) {
        $fulfill($value);
      },
      $handler
    );
  }

  /**
   * Settles the promises
   *
   * @param *int      $state
   * @param SplQueue|null $queue
   *
   * @return bool
   */
  protected function settle(int $state, SplQueue $queue = null): bool
  {
    //if there's no temporary queue
    if (is_null($queue)) {
      //create one
      $queue = new SplQueue();
    }

    //set the state
    $this->state = $state;

    //if the class queue is empty
    if ($this->queue->isEmpty()) {
      //switch out the queue with the temp
      $this->queue = $queue;
      return true;
    }

    //determine the index
    $index = 0;
    if ($state === self::STATUS_REJECTED) {
      $index = 1;
    }

    //get the callback
    $callbacks = $this->queue->dequeue();
    $callback = $callbacks[$index];

    //requeue the callback
    $queue->enqueue($callbacks);

    //callback is not callable
    if (!is_callable($callback)) {
      //move on to the next one
      return $this->settle($state, $queue);
    }

    //NOTE: We dont want to add the "then"
    //callbacks to the async handler because
    //the return value is passed linear to each
    //callback

    //call the callback
    try {
      $value = $callback($this->value);
    } catch (Throwable $e) {
      //set the value
      $this->value = $e->getMessage();

      //move on to the next one, but now rejected
      return $this->settle(static::STATUS_REJECTED, $queue);
    }

    //if the return value is a Promise
    if ($value instanceof Promise) {
      // @codeCoverageIgnoreStart
      //we need to wait for the value before continuing
      $fulfill = function ($value) use ($queue) {
        //set the value
        $this->value = $value;
        //continue to settle
        $this->settle(static::STATUS_FULFILLED, $queue);
      };

      $reject = function ($value) use ($queue) {
        //set the value
        $this->value = $value;
        //continue to settle
        $this->settle(static::STATUS_REJECTED, $queue);
      };
      // @codeCoverageIgnoreEnd

      $value->then($fulfill, $reject);

      return false;
    }

    //the index 2 flag is for finally() callback
    if (!isset($callbacks[2])) {
      $this->value = $value;
    }

    //move on to the next one
    return $this->settle($state, $queue);
  }

  /**
   * Appends fulfillment and rejection handlers to the promise, and returns
   * a new promise resolving to the return value of the called handler.
   *
   * See: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Promise/then
   *
   * @param callable $onFulfilled
   * @param callable $onRejected
   *
   * @return PromiseInterface
   */
  public function then(
    callable $onFulfilled = null,
    callable $onRejected = null
  ): PromiseInterface {
    $this->queue->enqueue([$onFulfilled, $onRejected]);

    if ($this->state === static::STATUS_FULFILLED
      && is_callable($onFulfilled)
    ) {
      $this->value = $onFulfilled($this->value);
    } else if ($this->state === static::STATUS_REJECTED
      && is_callable($onRejected)
    ) {
      $this->value = $onRejected($this->value);
    }

    return $this;
  }
}
