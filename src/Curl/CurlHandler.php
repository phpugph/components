<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\Curl;

use Closure;
use ArrayAccess;
use DOMDocument;

use UGComponents\Helper\InstanceTrait;
use UGComponents\Helper\LoopTrait;
use UGComponents\Helper\ConditionalTrait;

use UGComponents\Profiler\InspectorTrait;
use UGComponents\Profiler\LoggerTrait;

use UGComponents\Resolver\StateTrait;
use UGComponents\Resolver\ResolverException;

/**
 * Handles the formation and sending of cURL calls
 *
 * @vendor   UGComponents
 * @package  Curl
 * @standard PSR-2
 */
class CurlHandler implements ArrayAccess
{
  use InstanceTrait,
    LoopTrait,
    ConditionalTrait,
    InspectorTrait,
    LoggerTrait,
    StateTrait;

  /**
   * @const string ENCODE_JSON
   */
  const ENCODE_JSON = 'json';

  /**
   * @const string ENCODE_QUERY
   */
  const ENCODE_QUERY = 'query';

  /**
   * @const string ENCODE_XML
   */
  const ENCODE_XML = 'xml';

  /**
   * @const string ENCODE_RAW
   */
  const ENCODE_RAW = 'raw';

  /**
   * @const string METHOD_GET
   */
  const METHOD_GET = 'GET';

  /**
   * @const string METHOD_OPTIONS
   */
  const METHOD_OPTIONS = 'OPTIONS';

  /**
   * @const string METHOD_POST
   */
  const METHOD_POST = 'POST';

  /**
   * @const string METHOD_PATCH
   */
  const METHOD_PATCH = 'PATCH';

  /**
   * @const string METHOD_PUT
   */
  const METHOD_PUT = 'PUT';

  /**
   * @const string METHOD_DELETE
   */
  const METHOD_DELETE = 'DELETE';

  /**
   * @var Closure|null $mapCache The global curl callback
   */
  protected static $mapCache = null;

  /**
   * @var Closure|null $map The actual curl callback
   */
  protected $map = null;

  /**
   * @var array $options List of cURL options
   */
  protected $options = [];

  /**
   * @var array $meta Stored meta data
   */
  protected $meta = [];

  /**
   * @var array $query List of URL queries
   */
  protected $query = [];

  /**
   * @var array $headers List of headers
   */
  protected $headers = [];

  /**
   * Determines if the method is an actual curl option
   *
   * @param *string $name Name of method
   * @param *array  $args Arguments to pass
   *
   * @return mixed
   */
  public function __call($name, $args)
  {
    if (strpos($name, 'set') === 0) {
      //'AutoReferer' => CURLOPT_AUTOREFERER,
      $name = strtoupper(substr($name, 3));

      $key = constant('CURLOPT_' . $name);

      if (!is_null($key)) {
        $this->options[$key] = $args[0];
        return $this;
      }
    }

    try {
      return $this->__callResolver($name, $args);
    } catch (ResolverException $e) {
      throw new CurlException($e->getMessage());
    }
  }

  /**
   * Set a curl map, which is usually good for testing
   *
   * @param Closure $map
   */
  public function __construct(Closure $map = null)
  {
    // @codeCoverageIgnoreStart
    if (is_null(self::$mapCache)) {
      self::$mapCache = include(__DIR__ . '/map.php');
    }
    // @codeCoverageIgnoreEnd

    $this->map = self::$mapCache;

    if (!is_null($map)) {
      $this->map = $map;
    }
  }

  /**
   * Send the curl off and returns the results
   * parsed as DOMDocument
   *
   * @return DOMDOcument
   */
  public function getDomDocumentResponse()
  {
    $response = $this->getResponse();
    $xml = new DOMDocument();
    $xml->loadXML($response);
    return $xml;
  }

  /**
   * Send the curl off and returns the results
   * parsed as JSON
   *
   * @param bool $assoc To use associative array instead
   *
   * @return array
   */
  public function getJsonResponse($assoc = true)
  {
    $response = $this->getResponse();
    return json_decode($response, $assoc);
  }

  /**
   * Returns the meta of the last call
   *
   * @param string|null $key The name of the key in meta
   *
   * @return array
   */
  public function getMeta($key = null)
  {
    if (isset($this->meta[$key])) {
      return $this->meta[$key];
    }

    return $this->meta;
  }

  /**
   * Send the curl off and returns the results
   * parsed as url query
   *
   * @return array
   */
  public function getQueryResponse()
  {
    $response = $this->getResponse();
    parse_str($response, $query);
    return $query;
  }

  /**
   * Send the curl off and returns the results
   *
   * @return string
   */
  public function getResponse()
  {
    $this->addHeaders();
    $this->options[CURLOPT_RETURNTRANSFER] = true;

    $this->meta = call_user_func($this->map, $this->options);

    return $this->meta['response'];
  }

  /**
   * Send the curl off and returns the results
   * parsed as SimpleXml
   *
   * @return SimpleXmlElement
   */
  public function getSimpleXmlResponse()
  {
    $this->meta['response'] = $this->getResponse();
    return simplexml_load_string($this->meta['response']);
  }

  /**
   * isset using the ArrayAccess interface
   *
   * @param *scalar|null $offset The key to test if exists
   *
   * @return bool
   */
  public function offsetExists($offset)
  {
    if (is_string($offset)) {
      //if it doesn't have a CURL prefix
      if (strpos($offset, 'CURLOPT_') !== 0) {
        $offset = 'CURLOPT_' . $offset;
      }

      if (defined(strtoupper($offset))) {
        $offset = constant(strtoupper($offset));
      }
    }

    return isset($this->options[$offset]);
  }

  /**
   * returns data using the ArrayAccess interface
   *
   * @param *scalar|null $offset The key to get
   *
   * @return mixed
   */
  public function offsetGet($offset)
  {
    if (is_string($offset)) {
      //if it doesn't have a CURL prefix
      if (strpos($offset, 'CURLOPT_') !== 0) {
        $offset = 'CURLOPT_' . $offset;
      }

      if (defined(strtoupper($offset))) {
        $offset = constant(strtoupper($offset));
      }
    }

    return isset($this->options[$offset]) ? $this->options[$offset] : null;
  }

  /**
   * Sets data using the ArrayAccess interface
   *
   * @param *scalar|null $offset
   * @param mixed    $value
   */
  public function offsetSet($offset, $value)
  {
    if (is_string($offset)) {
      //if it doesn't have a CURL prefix
      if (strpos($offset, 'CURLOPT_') !== 0) {
        $offset = 'CURLOPT_' . $offset;
      }

      if (defined(strtoupper($offset))) {
        $offset = constant(strtoupper($offset));
      }
    }

    if (!is_null($offset)) {
      $this->options[$offset] = $value;
    }
  }

  /**
   * unsets using the ArrayAccess interface
   *
   * @param *scalar|null $offset The key to unset
   */
  public function offsetUnset($offset)
  {
    if (is_string($offset)) {
      //if it doesn't have a CURL prefix
      if (strpos($offset, 'CURLOPT_') !== 0) {
        $offset = 'CURLOPT_' . $offset;
      }

      if (defined(strtoupper($offset))) {
        $offset = constant(strtoupper($offset));
      }
    }

    if (isset($this->options[$offset])) {
      unset($this->options[$offset]);
    }
  }

  /**
   * Send the curl off
   *
   * @return CurlHandler
   */
  public function send()
  {
    $this->addHeaders();

    $this->meta = call_user_func($this->map, $this->options);

    return $this;
  }

  /**
   * Curl has problems handling custom request types
   * from misconfigured end points or vice versa.
   * When default cURL fails, try a custom GET instead
   *
   * @return CurlHandler
   */
  public function setCustomGet()
  {
    $this->setCustomRequest(self::METHOD_GET);
    return $this;
  }

  /**
   * Curl has problems handling custom request types
   * from misconfigured end points or vice versa.
   * When default cURL fails, try a custom OPTIONS instead
   *
   * @return CurlHandler
   */
  public function setCustomOptions()
  {
    $this->setCustomRequest(self::METHOD_OPTIONS);
    return $this;
  }

  /**
   * Curl has problems handling custom request types
   * from misconfigured end points or vice versa.
   * When default cURL fails, try a custom POST instead
   *
   * @return CurlHandler
   */
  public function setCustomPost()
  {
    $this->setCustomRequest(self::METHOD_POST);
    return $this;
  }

  /**
   * Curl has problems handling custom request types
   * from misconfigured end points or vice versa.
   * When default cURL fails, try a custom PATCH instead
   *
   * @return CurlHandler
   */
  public function setCustomPatch()
  {
    $this->setCustomRequest(self::METHOD_PATCH);
    return $this;
  }

  /**
   * Curl has problems handling custom request types
   * from misconfigured end points or vice versa.
   * When default cURL fails, try a custom PUT instead
   *
   * @return CurlHandler
   */
  public function setCustomPut()
  {
    $this->setCustomRequest(self::METHOD_PUT);
    return $this;
  }

  /**
   * Curl has problems handling custom request types
   * from misconfigured end points or vice versa.
   * When default cURL fails, try a custom DELETE instead
   *
   * @return CurlHandler
   */
  public function setCustomDelete()
  {
    $this->setCustomRequest(self::METHOD_DELETE);
    return $this;
  }

  /**
   * CURLOPT_POSTFIELDS accepts array and string
   * arguments, this is a special case that __call
   * does not handle
   *
   * @param *string|array $fields the post data to send
   * @param string    $type   query or json
   *
   * @return CurlHandler
   */
  public function setPostFields($fields, string $type = self::ENCODE_QUERY)
  {
    if (is_array($fields)) {
      if ($type === self::ENCODE_JSON) {
         $fields = json_encode($fields);
      } else {
         $fields = http_build_query($fields);
      }
    }

    $this->options[CURLOPT_POSTFIELDS] = $fields;

    return $this;
  }

  /**
   * Sets request headers
   *
   * @param *array|string $key   The header name
   * @param scalar|null   $value The header value
   *
   * @return CurlHandler
   */
  public function setHeaders($key, $value = null)
  {
    if (is_array($key)) {
      $this->headers = $key;
      return $this;
    }

    $this->headers[] = $key.': '.$value;
    return $this;
  }

  /**
   * Sets url parameter
   *
   * @param *array|string $key   The parameter name
   * @param scalar    $value The parameter value
   *
   * @return CurlHandler
   */
  public function setUrlParameter($key, $value = null)
  {
    if (is_array($key)) {
      $this->param = $key;
      return $this;
    }

    $this->param[$key] = $value;
    return $this;
  }

  /**
   * Sets CURLOPT_SSL_VERIFYHOST
   *
   * @param bool $on Flag to verify host
   *
   * @return CurlHandler
   */
  public function verifyHost($on = true)
  {
    $this->options[CURLOPT_SSL_VERIFYHOST] = $on ? 1 : 2;
    return $this;
  }

  /**
   * Sets CURLOPT_SSL_VERIFYPEER
   *
   * @param bool $on Flag to verify peer
   *
   * @return CurlHandler
   */
  public function verifyPeer($on = true)
  {
    $this->options[CURLOPT_SSL_VERIFYPEER] = $on;
    return $this;
  }

  /**
   * Adds extra headers to the cURL request
   *
   * @return CurlHandler
   */
  protected function addHeaders()
  {
    if (empty($this->headers)) {
      return $this;
    }

    $this->options[CURLOPT_HTTPHEADER] = $this->headers;
    return $this;
  }
}
