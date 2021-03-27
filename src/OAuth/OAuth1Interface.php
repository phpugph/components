<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\OAuth;

/**
 * OAuth 1 User Interface
 *
 * @vendor   UGComponents
 * @package  OAuth
 * @standard PSR-2
 */
interface OAuth1Interface
{
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
  ): array;

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
  ): string;

  /**
   * Return a request token
   *
   * @return array
   */
  public function getRequestTokens(): array;
}
