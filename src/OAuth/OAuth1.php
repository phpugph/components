<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\OAuth;

use Closure;

/**
 * OAuth 1 implementation
 *
 * @vendor   UGComponents
 * @package  OAuth
 * @standard PSR-2
 */
class OAuth1 extends AbstractOAuth1 implements OAuth1Interface
{
  /**
   * Sets up the required variables
   *
   * @param *string $consumerKey
   * @param *string $consumerSecret
   * @param *string $urlRedirect
   * @param *string $urlRequest
   * @param *string $urlAuthorize
   * @param *string $urlAccess
   */
  public function __construct(
    string $consumerKey,
    string $consumerSecret,
    string $urlRedirect,
    string $urlRequest,
    string $urlAuthorize,
    string $urlAccess,
    Closure $map = null
  ) {
    $this->consumerKey = $consumerKey;
    $this->consumerSecret = $consumerSecret;

    $this->urlRedirect = $urlRedirect;
    $this->urlRequest = $urlRequest;
    $this->urlAuthorize = $urlAuthorize;
    $this->urlAccess = $urlAccess;

    $this->time = time();
    $this->nonce = md5(uniqid(rand(), true));

    $this->signature = self::PLAIN_TEXT;
    $this->method = self::GET;

    $this->map = $map;
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
  public function getAccessTokens(
    string $responseToken,
    string $requestSecret,
    string $verifier
  ): array {
    return $this
      ->setUrl($this->urlAccess)
      ->useAuthorization()
      ->setMethodToPost()
      ->setToken($responseToken, $requestSecret)
      ->setVerifier($verifier)
      ->setSignatureToHmacSha1()
      ->getQueryResponse();
  }

  /**
   * Returns the URL used for login.
   *
   * @param *string $requestToken
   * @param bool  $force    force user re-login
   *
   * @return string
   */
  public function getLoginUrl(
    string $requestToken,
    bool $force = false
  ): string {
    //build the query
    $query = [
      'oauth_token' => $requestToken,
      'oauth_callback' => $this->urlRedirect,
      'force_login' => (int) $force
    ];

    $query = http_build_query($query);
    return $this->urlAuthorize . '?' . $query;
  }

  /**
   * Return a request token
   *
   * @return array
   */
  public function getRequestTokens(): array
  {
    return $this
      ->setUrl($this->urlRequest)
      ->useAuthorization()
      ->setMethodToPost()
      ->setSignatureToHmacSha1()
      ->getQueryResponse();
  }

  /**
   * Return resource
   *
   * @return array
   */
  public function get(
    string $url,
    array $accessToken,
    array $query = []
  ): array {
    return $this
      ->setUrl($url)
      ->useAuthorization()
      ->setMethodToGet()
      ->setToken($accessToken['oauth_token'], $accessToken['oauth_token_secret'])
      ->setSignatureToHmacSha1()
      ->getJsonResponse($query);
  }
}
