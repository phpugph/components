<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\OAuth;

use UGComponents\Curl\CurlHandler;

use Closure;

/**
 * OAuth 2 implementation
 *
 * @vendor   UGComponents
 * @package  OAuth
 * @standard PSR-2
 */
class OAuth2 extends AbstractOAuth2 implements OAuth2Interface
{
  /**
   * Sets up the required variables
   *
   * @param *string $clientId
   * @param *string $clientSecret
   * @param *string $urlRedirect
   * @param *string $urlRequest
   * @param *string $urlAccess
   * @param *string $urlResource
   * @param Closure $map
   */
  public function __construct(
    string $clientId,
    string $clientSecret,
    string $urlRedirect,
    string $urlRequest,
    string $urlAccess,
    string $urlResource,
    Closure $map = null
  ) {
    $this->clientId = $clientId;
    $this->clientSecret = $clientSecret;
    $this->urlRedirect = $urlRedirect;
    $this->urlRequest = $urlRequest;
    $this->urlAccess = $urlAccess;
    $this->urlResource = $urlResource;
    $this->map = $map;
  }

  /**
   * Returns the access token given the code
   *
   * @param string* $code
   *
   * @return array
   */
  public function getAccessTokens(string $code): array
  {
    //populate fields
    $query = [
      self::CLIENT_ID => $this->clientId,
      self::CLIENT_SECRET => $this->clientSecret,
      self::REDIRECT_URL => $this->urlRedirect,
      self::GRANT_TYPE => $this->grantType
    ];

    //if there is a code
    if (!is_null($code)) {
      //put codein the query
      $query[self::CODE] = $code;
    }

    //set curl
    $result = CurlHandler::i($this->map)
      ->setUrl($this->urlAccess)
      ->verifyHost(false)
      ->verifyPeer(false)
      ->setHeaders(self::TYPE, self::REQUEST)
      ->setPostFields(http_build_query($query))
      ->getResponse();

    //check if results is in JSON format
    if ($this->isJson($result)) {
      //if it is in json, lets json decode it
      $response =  json_decode($result, true);
    //else its not in json format
    } else {
      //parse it to make it an array
       parse_str($result, $response);
    }

    return $response;
  }

  /**
   * Returns the generated login url
   *
   * @return string
   */
  public function getLoginUrl(): string
  {
    //populate fields
    $query = [
      self::RESPONSE_TYPE => $this->responseType,
      self::CLIENT_ID => $this->clientId,
      self::REDIRECT_URL => $this->urlRedirect,
      self::ACCESS_TYPE => $this->accessType,
      self::APROVAL => $this->approvalPrompt
    ];

    if (!empty($this->scopes)) {
      $query['scope'] = implode(',', $this->scopes);
    }

    //if there is state
    if (!is_null($this->state)) {
      //add state to the query
      $query['state'] = $this->state;
    }

    //if there is display
    if (!is_null($this->display)) {
      //add state to the query
      $query['display'] = $this->display;
    }

    //generate a login url
    return $this->urlRequest . '?' . http_build_query($query);
  }

  /**
   * Returns the Resource Response
   *
   * @return string
   */
  public function get(array $query): array
  {
    // send request
    $result = CurlHandler::i($this->map)
      ->setUrl($this->urlResource . '?' . http_build_query($query))
      ->setCustomRequest('GET')
      ->getJsonResponse();

    return $result;
  }
}
