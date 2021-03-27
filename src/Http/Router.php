<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Http;

use UGComponents\Event\EventTrait;
use UGComponents\Event\EventEmitter;

use UGComponents\Resolver\ResolverTrait;

use UGComponents\IO\Request\RequestInterface;

use UGComponents\Http\Router\RouterInterface;

/**
 * Handles method-path matching and routing
 *
 * @vendor   UGComponents
 * @package  Http
 * @standard PSR-2
 */
class Router implements RouterInterface
{
  use EventTrait, ResolverTrait;

  /**
   * Allow to pass a custom EventEmitter
   */
  public function __construct(EventEmitter $handler = null)
  {
    //but we do need one
    if (is_null($handler)) {
      $handler = $this->resolve(EventEmitter::class);
    }

    $this->setEventEmitter($handler);
  }

  /**
   * Process routes
   *
   * @param *RequestInterface $request
   * @param mixed       ...$args
   *
   * @return bool
   */
  public function process(RequestInterface $request, ...$args): bool
  {
    $path = $request->getPath('string');
    $method = $request->getMethod();
    $event = $method.' '.$path;

    return $this
      ->getEventEmitter()
      ->emit($event, $request, ...$args)
      ->getMeta() !== EventEmitter::STATUS_INCOMPLETE;
  }

  /**
   * Adds routing middleware
   *
   * @param *string   $method   The request method
   * @param *string   $pattern  The route pattern
   * @param *callable $callback The middleware handler
   * @param int     $priority  if true will be prepended in order
   *
   * @return RouterInterface
   */
  public function route(
    string $method,
    string $pattern,
    callable $callback,
    int $priority = 0
  ): RouterInterface {
    //hard requirement
    if (!is_callable($callback)) {
      throw HttpException::forInvalidRouteCallback();
    }

    if (strtoupper($method) === 'ALL') {
      $method = '[a-zA-Z0-9]+';
    }

    //find and organize all the dynamic parameters
    preg_match_all('#(\:[a-zA-Z0-9\-_]+)|(\*\*)|(\*)#s', $pattern, $matches);

    $keys = array();
    if (isset($matches[0]) && is_array($matches[0])) {
      $keys = $matches[0];
    }

    //replace the :variable-_name01
    $regex = preg_replace('#(\:[a-zA-Z0-9\-_]+)#s', '*', $pattern);

    //replace the stars
    //* -> ([^/]+)
    $regex = str_replace('*', '([^/]+)', $regex);
    //** -> ([^/]+)([^/]+) -> (.*)
    $regex = str_replace('([^/]+)([^/]+)', '(.*)', $regex);

    //now form the event pattern
    $event = '#^' . $method . '\s' . $regex . '/*$#is';

    //we need the handler for later
    $handler = $this->getEventEmitter();

    $handler->on(
      $event,
      function (
        RequestInterface $request,
        ...$args
      ) use (
        $handler,
        $callback,
        $pattern,
        $keys
      ) {
        $route = $handler->getMeta();
        $variables = array();
        $parameters = array();

        //sanitize the variables
        foreach ($route['variables'] as $i => $variable) {
          //if it's a * variable
          if (!isset($keys[$i]) || strpos($keys[$i], '*') === 0) {
            //it's a variable
            if (strpos($variable, '/') === false) {
              $variables[] = $variable;
              continue;
            }

            $variables = array_merge($variables, explode('/', $variable));
            continue;
          }

          //if it's a :parameter
          if (isset($keys[$i])) {
            $key = substr($keys[$i], 1);
            $parameters[$key] = $variable;
          }
        }

        $request
          ->setStage($parameters)
          ->setRoute(array(
            'event' => $route['event'],
            'variables' => $variables,
            'parameters' => $parameters
          ));

        return call_user_func($callback, $request, ...$args);
      },
      $priority
    );

    return $this;
  }
}
