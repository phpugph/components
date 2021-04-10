<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Curl;

use Closure;
use UGComponents\Data\Model;

/**
 * An edenized rest pattern
 *
 * Usage:
 * $results = Rest::i('http://info.test.dev', true)
 *   //sets GET or POST parameters
 *   ->setFoo(['bar' => 'zoo'])
 *   //--> /friends
 *   ->friends()
 *   //--> /friends/user/comment/1/like
 *   ->createUserComment(1, 'like');
 *
 * @vendor   UGComponents
 * @package  Components
 * @standard PSR-2
 */
class Rest extends Model
{
  /**
   * @var array $headers
   */
  protected $headers = [];

  /**
   * @var Closure|null $map The actual curl callback
   */
  protected $map = null;

  /**
   * @var bool $meta
   */
  protected $meta = [
    'agent' => null,
    'request' => CurlHandler::ENCODE_QUERY,
    'response' => CurlHandler::ENCODE_JSON
  ];

  /**
   * @var string|null $host
   */
  protected $host = null;

  /**
   * @var array $data
   */
  protected $data = [];

  /**
   * @var array $paths
   */
  protected $paths = [];

  /**
   * @var array $query
   */
  protected $query = [];

  /**
   * Processes set, post, put, delete, get, etc.
   *
   * @param string $name
   * @param array  $args
   *
   * @return mixed
   */
  public function __call(string $name, array $args)
  {
    //default actions test
    //example: getFriendComments(1, 'like') --> GET /friend/comments/1/like
    switch (true) {
      case strpos($name, 'get') === 0:
        $path = $this->getPath('get', $name, $args);
        return $this->send(CurlHandler::METHOD_GET, $path);
      case strpos($name, 'create') === 0:
        $path = $this->getPath('create', $name, $args);
        return $this->send(CurlHandler::METHOD_POST, $path);
      case strpos($name, 'post') === 0:
        $path = $this->getPath('post', $name, $args);
        return $this->send(CurlHandler::METHOD_POST, $path);
      case strpos($name, 'update') === 0:
        $path = $this->getPath('update', $name, $args);
        return $this->send(CurlHandler::METHOD_PUT, $path);
      case strpos($name, 'put') === 0:
        $path = $this->getPath('put', $name, $args);
        return $this->send(CurlHandler::METHOD_PUT, $path);
      case strpos($name, 'remove') === 0:
        $path = $this->getPath('remove', $name, $args);
        return $this->send(CurlHandler::METHOD_DELETE, $path);
      case strpos($name, 'delete') === 0:
        $path = $this->getPath('delete', $name, $args);
        return $this->send(CurlHandler::METHOD_DELETE, $path);
    }

    //if it's a factory method match
    //example ->friends()
    if (count($args) === 0) {
      //add this to the path
      $this->paths[] = $this->getKey('', $name, '/');
      return $this;
    }

    // @codeCoverageIgnoreStart
    //let the parent handle the rest
    return parent::__call($name, $args);
    // @codeCoverageIgnoreEnd
  }

  /**
   * Sets up the host and if we are in tet mode
   *
   * @param string  $host
   * @param Closure $map
   */
  public function __construct(string $host, Closure $map = null)
  {
    $this->host = $host;
    $this->map = $map;
  }

  /**
   * Add headers into this request
   *
   * @param string|array $key
   * @param string|null  $value
   *
   * @return Rest
   */
  public function addHeader($key, $value = null)
  {
    //if it's an array
    if (is_array($key)) {
      //warning this overwrites existing headers
      $this->headers = $key;
      return $this;
    }

    //if the value is null
    if (is_null($value)) {
      $this->headers[] = $key;
      return $this;
    }

    //else it should be key value
    $this->headers[] = $key . ': ' . $value;
    return $this;
  }

  /**
   * Add query into this request
   *
   * @param string|array $key
   * @param string|null  $value
   *
   * @return Rest
   */
  public function addQuery($key, $value = null)
  {
    //if it's an array
    if (is_array($key)) {
      //warning this overwrites existing headers
      $this->query = $key;
      return $this;
    }

    //else it should be key value
    $this->query[$key] =  $value;
    return $this;
  }

  /**
   * Sets the user agent
   *
   * @param *string $agent
   *
   * @return REST
   */
  public function setUserAgent(string $agent)
  {
    $this->meta['agent'] = $agent;
    return $this;
  }

  /**
   * Sets the request data format
   *
   * @param *string $format
   *
   * @return REST
   */
  public function setRequestFormat(string $format)
  {
    $this->meta['request'] = $format;
    return $this;
  }

  /**
   * Sets the response data format
   *
   * @param *string $format
   *
   * @return REST
   */
  public function setResponseFormat(string $format)
  {
    $this->meta['response'] = $format;
    return $this;
  }

  /**
   * Sends off this request to cURL
   *
   * @param string $method
   * @param string $path
   * @param array  $meta
   *
   * @return mixed
   */
  public function send(string $method, string $path)
  {
    //extract the meta data
    $data = $this->data;
    $query = $this->query;
    $agent = $this->meta['agent'];
    $headers = $this->headers;

    //permutation groups
    $postable = $method === CurlHandler::METHOD_POST || $method === CurlHandler::METHOD_PUT;
    $custom = $method != CurlHandler::METHOD_GET && $method != CurlHandler::METHOD_POST;

    //resolve the data
    //if the method is a put or post
    if ($postable) {
      //figure out how to encode it
      switch ($this->meta['request']) {
        case CurlHandler::ENCODE_JSON:
          $data = json_encode($data);
          break;
        case CurlHandler::ENCODE_QUERY:
        default:
          $data = http_build_query($data);
          break;
      }
    //it's a get or delete
    } else {
      //let the data be the query
      $query = array_merge($data, $query);
    }

    //form the url
    $url = $this->host . $path;

    //if we have a query
    if (!empty($query)) {
      //add it on to the url
      $url .= '?' . http_build_query($query);
    }

    // send it into curl
    $request = CurlHandler::i($this->map)
      ->setUrl($url)
      // sets connection timeout to 10 sec.
      ->setConnectTimeout(10)
      // sets the follow location to true
      ->setFollowLocation(true)
      // set page timeout to 60 sec
      ->setTimeout(60)
      // verifying Peer must be boolean
      ->verifyPeer(false)
      //if the agent is set
      ->when($agent, function () use ($agent) {
        // set USER_AGENT
        $this->setUserAgent($agent);
      })
      //if there are headers
      ->when(!empty($headers), function () use ($headers) {
        // set headers
        $this->setHeaders($headers);
      })
      //set the custom request
      ->when($custom, function () use ($method) {
        $this->setCustomRequest($method);
      })
      //when post or put
      ->when($postable, function () use ($data) {
        if (empty($data)) {
          return;
        }

        //set the post data
        $this->setPostFields($data);
      });

    //how should we return the data ?
    switch ($this->meta['response']) {
      case CurlHandler::ENCODE_QUERY:
        $response = $request->getQueryResponse(); // get the query response
        break;
      case CurlHandler::ENCODE_JSON:
        $response = $request->getJsonResponse(); // get the json response
        break;
      // @codeCoverageIgnoreStart
      case CurlHandler::ENCODE_XML:
        $response = $request->getSimpleXmlResponse(); // get the xml response
        break;
      // @codeCoverageIgnoreEnd
      case CurlHandler::ENCODE_RAW:
      default:
        $response = $request->getResponse(); // get the raw response
        break;
    }

    //reset paths
    $this->paths = [];

    return $response;
  }

  /**
   * Used by magic methods, this is used to
   * parse out the method name and return
   * the translated meaning
   *
   * @param string $action
   * @param string $method
   * @param string $separator
   *
   * @return string
   */
  private function getKey(string $action, string $method, string $separator = '_')
  {
    //setSomeSample -> post/Some/Sample
    $key = preg_replace('/([A-Z0-9])/', $separator."$1", $method);
    //set/Some/Sample -> /some/sample
    return trim(strtolower(substr($key, strlen($action))), $separator);
  }

  /**
   * Returns the compiled path
   *
   * @param string $action
   * @param string $method
   * @param array  $args
   *
   * @return string
   */
  private function getPath($action, $method, $args)
  {
    //first get the key
    $key = $this->getKey($action, $method, '/');

    //add a trailing seperator
    $path = '/' . $key;

    //if there are paths
    if (!empty($this->paths)) {
      //prefix the paths to the path
      $path = '/' . implode('/', $this->paths) . $path;
    }

    //if there are arguments
    if (!empty($args)) {
      //add that too
      $path .= '/' . implode('/', $args);
    }

    return str_replace('//', '/', $path);
  }
}
