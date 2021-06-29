<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Terminal;

use UGComponents\IO\IOTrait;

use UGComponents\Event\EventTrait;
use UGComponents\Event\EventEmitter;

/**
 * You can optionally adopt these methods into an existing class
 *
 * @vendor   UGComponents
 * @package  Terminal
 * @standard PSR-2
 */
trait TerminalTrait
{
  use IOTrait,
    EventTrait
    {
      IOTrait::run as runIO;
  }

  /**
   * Main processor
   *
   * @param *Request  $request
   * @param *Response $response
   *
   * @return bool
   */
  public function main($request, $response): bool
  {
    $args = $request->getArgs();
    if (!isset($args[1])) {
      throw TerminalException::forArgumentCount();
    }

    $command = array_shift($args);
    $event = array_shift($args);

    if (!empty($args)) {
      $request->setStage(TerminalHelper::parseArgs($args));
    }

    $this->emit($event, $request, $response);

    switch ($this->getEventEmitter()->getMeta()) {
      case EventEmitter::STATUS_OK:
        $response->setStatus(200, TerminalHandler::STATUS_200);
        break;
      case EventEmitter::STATUS_NOT_FOUND:
        $response->setStatus(404, TerminalHandler::STATUS_404);
        break;
      case EventEmitter::STATUS_INCOMPLETE:
        $response->setStatus(308, TerminalHandler::STATUS_308);
        break;
    }

    if (!$response->isError() && $response->getStatus() === 404) {
      $response->setError(true, TerminalHandler::STATUS_404);
    }

    return !$response->isError();
  }

  /**
   * Process and output
   *
   * @param ?bool $emulate If you really want it to echo (for testing)
   *
   * @return bool
   */
  public function run(bool $emulate = false): bool
  {
    $response = $this->getResponse();

    if (!is_callable($this->processor)) {
      $this->processor = [$this, 'main'];
    }

    $continue = $this->prepare();
    if ($continue && $response->getStatus() == 200) {
      $continue = $this->process();
    }

    if (!$continue) {
      return false;
    }

    // @codeCoverageIgnoreStart
    //not testable
    //check for content
    $request = $this->getRequest();
    if ($response->getStatus() === 200
      && $request->hasStage('output')
      && !$emulate
    ) {
      if (!$response->hasContent() && $response->hasJson()) {
        $response->setContent($response->get('json'));
      }

      switch ($request->getStage('output')) {
        case 'boundary':
          $boundary = 'boundary-------------------------';
          TerminalHelper::output($boundary);
          TerminalHelper::output($response->getContent());
          TerminalHelper::output($boundary);
          break;
        case 'raw':
        default:
          TerminalHelper::output($response->getContent());
          break;
      }
    }

    return $this->shutdown();
    // @codeCoverageIgnoreEnd
  }
}
