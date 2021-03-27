<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\OAuth;

use DOMDocument;
use SimpleXmlElement;
use UGComponents\Curl\CurlHandler;

/**
 * OAuth 1 helpers
 *
 * @vendor   UGComponents
 * @package  OAuth
 * @standard PSR-2
 */
abstract class AbstractOAuth1
{
  /**
   * @const string HMAC_SHA1
   */
  const HMAC_SHA1 = 'HMAC-SHA1';

  /**
   * @const string RSA_SHA1
   */
  const RSA_SHA1 = 'RSA-SHA1';

  /**
   * @const string PLAIN_TEXT
   */
  const PLAIN_TEXT = 'PLAINTEXT';

  /**
   * @const string POST
   */
  const POST = 'POST';

  /**
   * @const string GET
   */
  const GET = 'GET';

  /**
   * @const string OAUTH_VERSION
   */
  const OAUTH_VERSION = '1.0';

  /**
   * @const string AUTH_HEADER
   */
  const AUTH_HEADER = 'Authorization: OAuth %s';

  /**
   * @const string POST_HEADER
   */
  const POST_HEADER = 'Content-Type: application/x-www-form-urlencoded';

  /**
   * @var string|null $consumerKey consumer_key
   */
  protected $consumerKey = null;

  /**
   * @var string|null $consumerSecret consumer_secret
   */
  protected $consumerSecret = null;

  /**
   * @var string|null $requestToken request_token
   */
  protected $requestToken = null;

  /**
   * @var string|null $requestSecret request_secret
   */
  protected $requestSecret = null;

  /**
   * @var bool $useAuthorization
   */
  protected $useAuthorization = false;

  /**
   * @var string|null $method
   */
  protected $method = null;

  /**
   * @var string|null $realm
   */
  protected $realm = null;

  /**
   * @var string|null $time
   */
  protected $time = null;

  /**
   * @var string|null $nonce
   */
  protected $nonce = null;

  /**
   * @var string|null $verifier
   */
  protected $verifier = null;

  /**
   * @var string|null $callback
   */
  protected $callback = null;

  /**
   * @var string|null $signature
   */
  protected $signature = null;

  /**
   * @var array $meta
   */
  protected $meta = [];

  /**
   * @var array $headers
   */
  protected $headers = [];

  /**
   * @var bool $json
   */
  protected $json = false;

  /**
   * @var string|null $url
   */
  protected $url = null;

  /**
   * @var string|null $urlAccess
   */
  protected $urlAccess = null;

  /**
   * @var string|null $urlAuthorize
   */
  protected $urlAuthorize = null;

  /**
   * @var string|null $urlRedirect
   */
  protected $urlRedirect = null;

  /**
   * @var string|null $urlRequest
   */
  protected $urlRequest = null;

  /**
   * Sets request headers
   *
   * @param *string $key
   * @param scalar  $value
   *
   * @return AbstractOAuth1
   */
  protected function addHeader(string $key, $value = null): AbstractOAuth1
  {
    $this->headers[] = $key.': '.$value;
    return $this;
  }

  /**
   * Builds a query with natural sorting
   *
   * @param *array $params
   * @param string $separator
   * @param bool   $noQuotes
   * @param bool   $subList
   *
   * @return string
   */
  protected function buildQuery(
    array $params,
    string $separator = '&',
    bool $noQuotes = true,
    bool $subList = false
  ): string {
    if (empty($params)) {
      return '';
    }

    //encode both keys and values
    $keys = $this->encode(array_keys($params));
    $values = $this->encode(array_values($params));

    $params = array_combine($keys, $values);

    // Parameters are sorted by name, using lexicographical byte value ordering.
    // http://oauth.net/core/1.0/#rfc.section.9.1.1
    uksort($params, 'strcmp');

    // Turn params array into an array of "key=value" strings
    foreach ($params as $key => $value) {
      if (is_array($value)) {
        // If two or more parameters share the same name,
        // they are sorted by their value. OAuth Spec: 9.1.1 (1)
        natsort($value);
        $params[$key] = $this->buildQuery($value, $separator, $noQuotes, true);
        continue;
      }

      if (!$noQuotes) {
        $value = '"'.$value.'"';
      }

      $params[$key] = $value;
    }

    if ($subList) {
      return $params;
    }

    foreach ($params as $key => $value) {
      $params[$key] = $key.'='.$value;
    }

    return implode($separator, $params);
  }

  protected function encode($string)
  {
    if (is_array($string)) {
      foreach ($string as $i => $value) {
        $string[$i] = $this->encode($value);
      }

      return $string;
    }

    if (is_scalar($string)) {
      return str_replace('%7E', '~', rawurlencode($string));
    }

    return '';
  }

  /**
   * Deccodes a url
   *
   * @param *string $string
   *
   * @return string
   */
  protected function decode(string $string): string
  {
    return rawurldecode($string);
  }

  /**
   * Returns the access token
   *
   * @param *string $responseToken
   * @param *string $requestSecret from getRequestToken() usually
   * @param *string $verifier
   *
   * @return array
   */
  abstract public function getAccessTokens(
    string $responseToken,
    string $requestSecret,
    string $verifier
  ): array;

  /**
   * Returns the authorization header string
   *
   * @param *string $signature
   * @param bool  $string
   *
   * @return string
   */
  protected function getAuthorization(string $signature, bool $string = true)
  {
    //this is all possible configurations
    $params = [
      'realm'            => $this->realm,
      'oauth_consumer_key'     => $this->consumerKey,
      'oauth_token'        => $this->requestToken,
      'oauth_signature_method'  => self::HMAC_SHA1,
      'oauth_signature'      => $signature,
      'oauth_timestamp'      => $this->time,
      'oauth_nonce'        => $this->nonce,
      'oauth_version'        => self::OAUTH_VERSION,
      'oauth_verifier'      => $this->verifier,
      'oauth_callback'      => $this->callback
    ];

    //if no realm
    if (is_null($this->realm)) {
      //remove it
      unset($params['realm']);
    }

    //if no token
    if (is_null($this->requestToken)) {
      //remove it
      unset($params['oauth_token']);
    }

    //if no verifier
    if (is_null($this->verifier)) {
      //remove it
      unset($params['oauth_verifier']);
    }


    //if no callback
    if (is_null($this->callback)) {
      //remove it
      unset($params['oauth_callback']);
    }

    if (!$string) {
      return $params;
    }

    return sprintf(self::AUTH_HEADER, $this->buildQuery($params, ',', false));
  }

  /**
   * Returns the results
   * parsed as DOMDocument
   *
   * @param array $query
   *
   * @return DOMDocument
   */
  protected function getDomDocumentResponse(array $query = []): DOMDocument
  {
    $xml = new DOMDocument();
    $xml->loadXML($this->getResponse($query));
    return $xml;
  }

  /**
   * Returns the signature
   *
   * @return string
   */
  protected function getHmacPlainTextSignature(): string
  {
    return $this->consumerSecret . '&' . $this->tokenSecret;
  }

  /**
   * Returns the signature
   *
   * @param array $query
   *
   * @return string
   */
  protected function getHmacSha1Signature(array $query = []): string
  {
    //this is like the authorization params minus the realm and signature
    $params = [
      'oauth_consumer_key' => $this->consumerKey,
      'oauth_token' => $this->requestToken,
      'oauth_signature_method' => self::HMAC_SHA1,
      'oauth_timestamp' => $this->time,
      'oauth_nonce' => $this->nonce,
      'oauth_version' => self::OAUTH_VERSION,
      'oauth_verifier' => $this->verifier,
      'oauth_callback' => $this->callback
    ];

    //if no token
    if (is_null($this->requestToken)) {
      //unset that parameter
      unset($params['oauth_token']);
    }

    //if no token
    if (is_null($this->verifier)) {
      //unset that parameter
      unset($params['oauth_verifier']);
    }

    //if no callback
    if (is_null($this->callback)) {
      //remove it
      unset($params['oauth_callback']);
    }

    $query = array_merge($params, $query); //merge the params and the query
    $query = $this->buildQuery($query); //make query into a string

    //create the base string
    $string = [
      $this->method,
      $this->encode($this->url),
      $this->encode($query)
    ];

    $string = implode('&', $string);

    //create the encryption key
    $key = $this->encode($this->consumerSecret) . '&' . $this->encode($this->requestSecret);

    //authentication method
    return base64_encode(hash_hmac('sha1', $string, $key, true));
  }

  /**
   * Returns the json response from the server
   *
   * @param array $query
   *
   * @return array
   */
  protected function getJsonResponse(array $query = []): array
  {
    return json_decode($this->getResponse($query), true);
  }

  /**
   * Returns the URL used for login.
   *
   * @param *string $requestToken
   * @param bool  $force    force user re-login
   *
   * @return string
   */
  abstract public function getLoginUrl(
    string $requestToken,
    bool $force = false
  ): string;

  /**
   * Returns the meta of the last call
   *
   * @param string $key
   *
   * @return array
   */
  protected function getMeta(string $key = null)
  {
    if (isset($this->meta[$key])) {
      return $this->meta[$key];
    }

    return $this->meta;
  }

  /**
   * Returns the query response from the server
   *
   * @param array $query
   *
   * @return array
   */
  protected function getQueryResponse(array $query = []): array
  {
    parse_str($this->getResponse($query), $response);
    return $response;
  }

  /**
   * Return a request token
   *
   * @return array
   */
  abstract public function getRequestTokens(): array;

  /**
   * Returns the token from the server
   *
   * @param array $query
   *
   * @return string
   */
  protected function getResponse(array $query = []): string
  {
    $headers = $this->headers;
    $json = null;
    if ($this->json) {
      $json = json_encode($query);
      $query = [];
    }

    //get the authorization parameters as an array
    $signature = $this->getSignature($query);
    $authorization = $this->getAuthorization($signature, false);

    //if we should use the authrization
    if ($this->useAuthorization) {
      //add the string to headers
      $headers[] = sprintf(self::AUTH_HEADER, $this->buildQuery($authorization, ',', false));
    } else {
      //merge authorization and query
      $query = array_merge($authorization, $query);
    }

    $query = $this->buildQuery($query);
    $url = $this->url;

    //set curl
    $curl = CurlHandler::i()
      ->verifyHost(false)
      ->verifyPeer(false);

    //if post
    if ($this->method === self::POST) {
      $headers[] = self::POST_HEADER;

      if (!is_null($json)) {
        $query = $json;
      }

      //get the response
      $response = $curl
        ->setUrl($url)
        ->setPost(true)
        ->setPostFields($query)
        ->setHeaders($headers)
        ->getResponse();
    } else {
      if (trim($query)) {
        //determine the conector
        $connector = null;

        //if there is no question mark
        if (strpos($url, '?') === false) {
          $connector = '?';
        //if the redirect doesn't end with a question mark
        } else if (substr($url, -1) != '?') {
          $connector = '&';
        }

        //now add the secret to the redirect
        $url .= $connector . $query;
      }

      //get the response
      $response = $curl
        ->setUrl($url)
        ->setHeaders($headers)
        ->getResponse();
    }

    $this->meta = $curl->getMeta();
    $this->meta['url'] = $url;
    $this->meta['authorization'] = $authorization;
    $this->meta['headers'] = $headers;
    $this->meta['query'] = $query;
    $this->meta['response'] = $response;

    return $response;
  }

  /**
   * Returns the signature based on what signature method was set
   *
   * @param array $query
   *
   * @return string
   */
  protected function getSignature(array $query = []): string
  {
    switch ($this->signature) {
      case self::HMAC_SHA1:
        return $this->getHmacSha1Signature($query);
      case self::RSA_SHA1:
      case self::PLAIN_TEXT:
      default:
        return $this->getHmacPlainTextSignature();
    }
  }

  /**
   * Returns the results parsed as SimpleXml
   *
   * @param array $query
   *
   * @return SimpleXmlElement
   */
  protected function getSimpleXmlResponse(array $query = []): SimpleXmlElement
  {
    return simplexml_load_string($this->getResponse($query));
  }

  /**
   * When sent, sends the parameters as post fields
   *
   * @return AbstractOAuth1
   */
  protected function jsonEncodeQuery(): AbstractOAuth1
  {
    $this->json = true;
    return $this;
  }

  /**
   * Parses a string into an array
   *
   * @param *string $string
   *
   * @return array
   */
  protected function parseString(string $string): array
  {
    $array = [];

    if (strlen($string) < 1) {
      return $array;
    }

    // Separate single string into an array of "key=value" strings
    $keyvalue = explode('&', $query_string);
    // Separate each "key=value" string into an array[key] = value
    foreach ($keyvalue as $pair) {
      list($k, $v) = explode('=', $pair, 2);

      // Handle the case where multiple values map to the same key
      // by pulling those values into an array themselves
      if (isset($query_array[$k])) {
        // If the existing value is a scalar, turn it into an array
        if (is_scalar($query_array[$k])) {
          $query_array[$k] = [ $query_array[$k] ];
        }
        array_push($query_array[$k], $v);
      } else {
        $query_array[$k] = $v;
      }
    }

    return $array;
  }

  /**
   * Sets the callback for authorization
   * This should be set if wanting an access token
   *
   * @param *string $url
   *
   * @return AbstractOAuth1
   */
  protected function setCallback(string $url): AbstractOAuth1
  {
    $this->callback = $url;
    return $this;
  }

  /**
   * Sets request headers
   *
   * @param *array $headers
   *
   * @return AbstractOAuth1
   */
  protected function setHeaders(array $headers): AbstractOAuth1
  {
    $this->headers = $headers;
    return $this;
  }

  /**
   * Sets the active URL
   *
   * @param *string $url
   *
   * @return AbstractOAuth1
   */
  protected function setUrl(string $url): AbstractOAuth1
  {
    $this->url = $url;
    return $this;
  }

  /**
   * When sent, appends the parameters to the URL
   *
   * @return AbstractOAuth1
   */
  protected function setMethodToGet(): AbstractOAuth1
  {
    $this->method = self::GET;
    return $this;
  }

  /**
   * When sent, sends the parameters as post fields
   *
   * @return AbstractOAuth1
   */
  protected function setMethodToPost(): AbstractOAuth1
  {
    $this->method = self::POST;
    return $this;
  }

  /**
   * Some Oauth servers requires a realm to be set
   *
   * @param string $realm
   *
   * @return AbstractOAuth1
   */
  protected function setRealm(string $realm): AbstractOAuth1
  {
    $this->realm = $realm;
    return $this;
  }

  /**
   * Sets the signature encryption type to HMAC-SHA1
   *
   * @return AbstractOAuth1
   */
  protected function setSignatureToHmacSha1(): AbstractOAuth1
  {
    $this->signature = self::HMAC_SHA1;
    return $this;
  }

  /**
   * Sets the signature encryption to RSA-SHA1
   *
   * @return AbstractOAuth1
   */
  protected function setSignatureToRsaSha1(): AbstractOAuth1
  {
    $this->signature = self::RSA_SHA1;
    return $this;
  }

  /**
   * Sets the signature encryption to PLAINTEXT
   *
   * @return AbstractOAuth1
   */
  protected function setSignatureToPlainText(): AbstractOAuth1
  {
    $this->signature = self::PLAIN_TEXT;
    return $this;
  }

  /**
   * Sets the request token and secret.
   * This should be set if wanting an access token
   *
   * @param *string $token
   * @param *string $secret
   *
   * @return AbstractOAuth1
   */
  protected function setToken(string $token, string $secret): AbstractOAuth1
  {
    $this->requestToken = $token;
    $this->requestSecret = $secret;

    return $this;
  }

  /**
   * Some Oauth servers requires a verifier to be set
   * when retrieving an access token
   *
   * @param *string $verifier
   *
   * @return AbstractOAuth1
   */
  protected function setVerifier(string $verifier): AbstractOAuth1
  {
    $this->verifier = $verifier;
    return $this;
  }

  /**
   * When sent, appends the authroization to the headers
   *
   * @param bool $use
   *
   * @return AbstractOAuth1
   */
  protected function useAuthorization(bool $use = true): AbstractOAuth1
  {
    $this->useAuthorization = $use;
    return $this;
  }
}
