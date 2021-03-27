<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\IO;

use Throwable;
use Exception;

use UGComponents\IO\Request\RequestTrait;
use UGComponents\IO\Response\ResponseTrait;
use UGComponents\IO\Middleware\PreProcessorTrait;
use UGComponents\IO\Middleware\PostProcessorTrait;
use UGComponents\IO\Middleware\ErrorProcessorTrait;

/**
 * You can optionally adopt these methods into an existing class
 *
 * @vendor   UGComponents
 * @package  IO
 * @standard PSR-2
 */
trait IOTrait
{
  use PreProcessorTrait,
    PostProcessorTrait,
    ErrorProcessorTrait,
    RequestTrait,
    ResponseTrait;

  protected $processor = null;

  /**
   * Prepares the event and calls the preprocessors
   *
   * @return bool Whether if the process should continue
   */
  public function prepare(): bool
  {
    $request = $this->getRequest();
    $response = $this->getResponse();
    $thrown = false;

    try {
      //dispatch an init
      $continue = $this->getPreprocessor()->process($request, $response);
    //we are going to ignore this because
    //there is a conflict between PHP5.6 and PHP7
    //and how it deals with Throwable
    // @codeCoverageIgnoreStart
    } catch (Throwable $e) {
      //if there is an exception
      //get the error processor
      $processor = $this->getErrorProcessor();
      //if no error processors
      if ($processor->isEmpty()) {
        //throw it out with anger.. grrr.
        throw $e;
      }

      //you may not want to out right throw it out
      $thrown = true;
      $response->setStatus(500, IOHandler::STATUS_500);
      $continue = $processor->process($request, $response, $e);
      //if there's an error in the errorware then let it be thrown
    } catch (Exception $e) {
      if (!$thrown) {
        //same logic as above
        //get the error processor
        $processor = $this->getErrorProcessor();
        //if no error processors
        if ($processor->isEmpty()) {
          //throw it out with anger.. grrr.
          throw $e;
        }

        //you may not want to out right throw it out
        $thrown = true;
        $response->setStatus(500, IOHandler::STATUS_500);
        $continue = $processor->process($request, $response, $e);
        //if there's an error in the errorware then let it be thrown
      }
    }
    // @codeCoverageIgnoreEnd

    return $continue;
  }

  /**
   * Handles the main routing process
   *
   * @param ?callable $callback
   *
   * @return bool Whether if the process should continue
   */
  public function process(): bool
  {
    if (!is_callable($this->processor)) {
      return true;
    }

    $request = $this->getRequest();
    $response = $this->getResponse();
    $thrown = false;

    try {
      //dispatch an init
      $continue = call_user_func($this->processor, $request, $response);
    //we are going to ignore this because
    //there is a conflict between PHP5.6 and PHP7
    //and how it deals with Throwable
    // @codeCoverageIgnoreStart
    } catch (Throwable $e) {
      //if there is an exception
      //get the error processor
      $processor = $this->getErrorProcessor();
      //if no error processors
      if ($processor->isEmpty()) {
        //throw it out with anger.. grrr.
        throw $e;
      }

      //you may not want to out right throw it out
      $thrown = true;
      $response->setStatus(500, IOHandler::STATUS_500);
      $continue = $processor->process($request, $response, $e);
      //if there's an error in the errorware then let it be thrown
    } catch (Exception $e) {
      if (!$thrown) {
        //same logic as above
        //get the error processor
        $processor = $this->getErrorProcessor();
        //if no error processors
        if ($processor->isEmpty()) {
          //throw it out with anger.. grrr.
          throw $e;
        }

        //you may not want to out right throw it out
        $thrown = true;
        $response->setStatus(500, IOHandler::STATUS_500);
        $continue = $processor->process($request, $response, $e);
        //if there's an error in the errorware then let it be thrown
      }
    }
    // @codeCoverageIgnoreEnd

    return $continue;
  }

  /**
   * Process and output
   *
   * @param ?callable $callback
   *
   * @return bool
   */
  public function run(): bool
  {
    $response = $this->getResponse();

    return $this->prepare()
      && $response->getStatus() == 200
      && $this->process()
      && $this->shutdown();
  }

  /**
   * Sets the main processor
   *
   * @param *callable $processor
   *
   * @return this
   */
  public function setProcessor(callable $processor)
  {
    $this->processor = $processor;
    return $this;
  }

  /**
   * This is called after it is outputted and the connection is closed
   *
   * @return bool Whether if the process should continue
   */
  public function shutdown(): bool
  {
    $request = $this->getRequest();
    $response = $this->getResponse();
    $thrown = false;

    try {
      //dispatch an init
      $continue = $this->getPostprocessor()->process($request, $response);
    //we are going to ignore this because
    //there is a conflict between PHP5.6 and PHP7
    //and how it deals with Throwable
    // @codeCoverageIgnoreStart
    } catch (Throwable $e) {
      //if there is an exception
      //get the error processor
      $processor = $this->getErrorProcessor();
      //if no error processors
      if ($processor->isEmpty()) {
        //throw it out with anger.. grrr.
        throw $e;
      }

      //you may not want to out right throw it out
      $thrown = true;
      $response->setStatus(500, IOHandler::STATUS_500);
      $continue = $processor->process($request, $response, $e);
      //if there's an error in the errorware then let it be thrown
    } catch (Exception $e) {
      if (!$thrown) {
        //same logic as above
        //get the error processor
        $processor = $this->getErrorProcessor();
        //if no error processors
        if ($processor->isEmpty()) {
          //throw it out with anger.. grrr.
          throw $e;
        }

        //you may not want to out right throw it out
        $thrown = true;
        $response->setStatus(500, IOHandler::STATUS_500);
        $continue = $processor->process($request, $response, $e);
        //if there's an error in the errorware then let it be thrown
      }
    }
    // @codeCoverageIgnoreEnd

    return $continue;
  }
}
