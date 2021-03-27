<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Async;

use PHPUnit\Framework\TestCase;

class Async_Promise_Test extends TestCase
{
  /**
   * @var
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp()
  {
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown()
  {
  }

  /**
   * @covers UGComponents\Async\Promise::then
   * @covers UGComponents\Async\Promise::__construct
   * @covers UGComponents\Async\Promise::getCoroutine
   * @covers UGComponents\Async\Promise::settle
   */
  public function testThen()
  {
    $handler = new AsyncHandler('noop');
    $promise = Promise::i(function($fulfill, $reject) {
      $fulfill(1);
    }, $handler);

    $test = $this;
    $called = false;
    $promise
      ->then(function ($value) {
        return $value + 1;
      })
      ->then(function ($value) use ($test, &$called) {
        $called = true;
        $test->assertEquals(2, $value);
      });

    $handler->run();
    $this->assertTrue($called);

    // test error
    $item = 0;
    $error = false;
    $promise1 = Promise::i(function($fulfill) {
      // this is a typo and should result to an error
      $fullfill(10);
    }, $handler);

    $called = false;
    $promise1
      ->then(function ($value) use (&$item) {
        $item = $value;
      })
      ->catch(function($err) use (&$error) {
        $error =  true;
      });

    $handler->run();

    $this->assertEquals(0, $item);
    $this->assertTrue($error);
  }

  /**
   * @covers UGComponents\Async\Promise::catch
   * @covers UGComponents\Async\Promise::__construct
   * @covers UGComponents\Async\Promise::getCoroutine
   * @covers UGComponents\Async\Promise::settle
   */
  public function testCatch()
  {
    $handler = new AsyncHandler('noop');
    $promise = Promise::i(function($fulfill, $reject) {
      $reject(1);
    }, $handler);

    $test = $this;
    $called = false;
    $promise
      ->catch(function ($value) {
        return $value + 1;
      })
      ->catch(function ($value) use ($test, &$called) {
        $called = true;
        $test->assertEquals(2, $value);
      });

    $handler->run();
    $this->assertTrue($called);
  }

  /**
   * @covers UGComponents\Async\Promise::then
   * @covers UGComponents\Async\Promise::catch
   * @covers UGComponents\Async\Promise::finally
   * @covers UGComponents\Async\Promise::getState
   * @covers UGComponents\Async\Promise::__construct
   * @covers UGComponents\Async\Promise::getCoroutine
   * @covers UGComponents\Async\Promise::settle
   */
  public function testFinally()
  {
    $handler = new AsyncHandler('noop');
    $promise = Promise::i(function($fulfill, $reject) {
      $fulfill(1);
    }, $handler);

    $test = $this;
    $called = 0;

    $promise
      ->then(function ($x) {
        return $x + 1;
      })
      ->then(function ($x) {
        return $x + 1;
      })
      ->finally(function () use (&$promise, &$test, &$called) {
        $called ++;
        $test->assertEquals(1, $promise->getState());
      })
      ->then(function ($x) use (&$promise, &$test, &$called) {
        $called ++;
        $test->assertEquals(3, $x);
        throw new Exception('fail');
      })
      ->catch(function ($message) use (&$promise, &$test, &$called) {
        $called ++;
        $test->assertEquals(2, $promise->getState());

        // overwrite state
        $test->assertEquals(3, $promise->getState(3));
        $test->assertEquals('fail', $message);
      });

    $handler->run();
    $this->assertEquals(3, $called);

    // test finally without triggering fullfill
    $called = 0;
    $promise1 = Promise::i(function($fulfill, $reject) use (&$called) {
      $called += 1;
    }, $handler);

    $promise
      ->finally(function () use (&$called) {
        $called++;
      });

    $handler->run();
    $this->assertEquals(2, $called);
  }

  /**
   * @covers UGComponents\Async\Promise::reject
   * @covers UGComponents\Async\Promise::__construct
   * @covers UGComponents\Async\Promise::getCoroutine
   * @covers UGComponents\Async\Promise::then
   * @covers UGComponents\Async\Promise::catch
   * @covers UGComponents\Async\Promise::settle
   */
  public function testReject()
  {
    $handler = new AsyncHandler('noop');

    $test = $this;
    $called = false;
    Promise::reject(1, $handler)
      ->then(null, function ($value) {
        return $value + 1;
      })
      ->catch(function ($value) use ($test, &$called) {
        $called = true;
        $test->assertEquals(2, $value);
      });

    $handler->run();
    $this->assertTrue($called);
  }

  /**
   * @covers UGComponents\Async\Promise::resolve
   * @covers UGComponents\Async\Promise::__construct
   * @covers UGComponents\Async\Promise::getCoroutine
   * @covers UGComponents\Async\Promise::then
   * @covers UGComponents\Async\Promise::catch
   * @covers UGComponents\Async\Promise::settle
   */
  public function testResolve()
  {
    $handler = new AsyncHandler('noop');

    $test = $this;
    $called = false;
    Promise::resolve(1, $handler)
      ->then(function ($value) {
        return $value + 1;
      })
      ->then(function ($value) use ($test, &$called) {
        $called = true;
        $test->assertEquals(2, $value);
      });

    $handler->run();
    $this->assertTrue($called);
  }

  /**
   * @covers UGComponents\Async\Promise::all
   * @covers UGComponents\Async\Promise::__construct
   * @covers UGComponents\Async\Promise::getCoroutine
   * @covers UGComponents\Async\Promise::then
   * @covers UGComponents\Async\Promise::catch
   * @covers UGComponents\Async\Promise::settle
   */
  public function testAll()
  {
    $handler = new AsyncHandler('noop');
    $promise1 = new Promise(function($fulfill) {
      for($i = 0; $i < 10; $i++) {
        $routine = yield;
        yield $i;
      }

      $fulfill($i);
    }, $handler);

    $handler->run();

    $promise2 = new Promise(function($fulfill) {
      for($i = 0; $i < 5; $i++) {
        yield $i;
      }

      $fulfill($i);
    }, $handler);

    $called = false;
    $error = null;
    $test = $this;

    Promise::all([$promise1, $promise2], $handler)->then(function($values) use (&$called, $test) {
      $called = true;
      $test->assertEquals(10, $values[0]);
      $test->assertEquals(5, $values[1]);
    })->catch(function($err) use (&$error) {
      $error = $err;
    });

    $handler->run();

    $test->assertNull($error);
    $test->assertTrue($called);

    // test 1 promise and 1 not promise
    $promise3 = new Promise(function($fulfill) {
      for($i = 0; $i < 10; $i++) {
        $routine = yield;
        yield $i;
      }

      $fulfill($i);
    }, $handler);

    $handler->run();

    $promise4 = 600;

    $called = false;
    $error = null;
    Promise::all([$promise3, $promise4], $handler)->then(function($values) use (&$called, $test) {
      $called = true;
      $test->assertEquals(10, $values[0]);
      $test->assertEquals(600, $values[1]);
    })->catch(function($err) use (&$error) {
      $error = $err;
    });

    $handler->run();

    $test->assertNull($error);
    $test->assertTrue($called);

    // Test Rejections
    $promise5 = function() {
      for($i = 0; $i < 5; $i++) {
        yield $i;
      }
    };

    $promise6 = new Promise(function() {
      for($i = 0; $i < 5; $i++) {
        yield $i;
      }

      $fulfill($i);
    }, $handler);

    $handler->run();

    $promise7 = new Promise(function($fulfill, $reject) {
      for($i = 0; $i <= 3; $i++) {
        if ($i == 2) {
          $reject($i);
        }

        yield $i;
      }

      $fulfill($i);
    }, $handler);

    $called = false;
    $error = null;
    Promise::all([$promise5, $promise6, $promise7], $handler)
    ->then(function($values) use (&$called, $test) {
      $test->assertEquals(8, $values[0]);
    })->catch(function($err) use (&$error) {
      $error = $err;
    });

    $handler->run(function($value) {});

    $test->assertNotEmpty($error);
    $test->assertFalse($called);

    // test fulfill, reject, fulfill
    $promise8 = new Promise(function($fulfill, $reject) {
      $fulfill(10);
    }, $handler);

    $handler->run();

    $promise9 = new Promise(function($fulfill, $reject) {
      for($i = 0; $i <= 3; $i++) {
        if ($i == 2) {
          $reject($i);
        }

        yield $i;
      }

      $fulfill($i);
    }, $handler);

    $handler->run();

    $promise10 = new Promise(function($fulfill, $reject) {
      $fulfill(11);
    }, $handler);

    $called = false;
    Promise::all([$promise8, $promise9, $promise10], $handler)->then(function($values) use (&$called, $test) {
      $called = true;
    })->catch(function($err) use (&$error) {
      $error = $err;
    });

    $handler->run();

    $test->assertNotEmpty($error);
    $test->assertFalse($called);
  }

  /**
   * @covers UGComponents\Async\Promise::race
   * @covers UGComponents\Async\Promise::__construct
   * @covers UGComponents\Async\Promise::getCoroutine
   * @covers UGComponents\Async\Promise::then
   * @covers UGComponents\Async\Promise::catch
   * @covers UGComponents\Async\Promise::settle
   */
  public function testRace()
  {
    $handler = new AsyncHandler('noop');

    $promise1 = new Promise(function($fulfill) {
      for($i = 0; $i < 10; $i++) {
        yield $i;
      }

      $fulfill($i);
    }, $handler);

    $promise2 = new Promise(function($fulfill) {
      for($i = 0; $i < 5; $i++) {
        yield $i;
      }

      $fulfill($i);
    }, $handler);

    $called = false;
    $error = null;
    $test = $this;

    Promise::race([$promise1, $promise2], $handler)->then(function($value) use (&$called, $test) {
      $called = true;
      $test->assertEquals(5, $value);
    })->catch(function($err) use (&$error) {
      $error = $err;
    });

    $handler->run();
    $this->assertNull($error);
    $this->assertTrue($called);

    // Test Rejections
    $promise3 = new Promise(function($fullfill, $reject) {
      $reject('yes');
    }, $handler);

    $promise4 = new Promise(function($fulfill, $reject) {
      for($i = 0; $i <= 10; $i++) {
        yield $i;
      }

      $reject($i + 1);
    }, $handler);


    $called = false;
    Promise::race([$promise3, $promise4], $handler)->then(function($value) use (&$called, $test) {
      $called = true;
      $test->assertEquals(8, $value);
    })->catch(function($err) use (&$error) {
      $error = $err;
    });

    $handler->run(function($value) {
    });

    $test->assertNotEmpty($error);
    $test->assertFalse($called);

    // test not promise
    $promise5 = 500;
    $promise6 = new Promise(function($fulfill) {
      for($i = 0; $i < 5; $i++) {
        yield $i;
      }

      $fulfill($i);
    }, $handler);

    $called = false;
    $error = null;
    Promise::race([$promise5, $promise6], $handler)->then(function($value) use (&$called, $test) {
      $called = true;
      $test->assertEquals(500, $value);
    })->catch(function($err) use (&$error) {
      $error = $err;
    });

    $handler->run(function($value) {
    });

    $test->assertNull($error);
    $test->assertTrue($called);
  }
}
