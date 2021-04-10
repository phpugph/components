<?php //-->
/**
 * This file is part of the UGComponents Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace UGComponents\OAuth;

use UGComponents\Curl\CurlHandler;

/**
 * OAuth 2 helpers
 *
 * @vendor   UGComponents
 * @package  OAuth
 * @standard PSR-2
 */
abstract class AbstractOAuth2
{
  /**
   * @const string ACCESS_TYPE
   */
  const ACCESS_TYPE = 'access_type';

  /**
   * @const string APROVAL
   */
  const APROVAL = 'approval_prompt';

  /**
   * @const string AUTHORIZATION
   */
  const AUTHORIZATION = 'authorization_code';

  /**
   * @const string AUTO - Approval Prompt Option
   */
  const AUTO = 'auto';

  /**
   * @const string CLIENT_ID
   */
  const CLIENT_ID = 'client_id';

  /**
   * @const string CLIENT_SECRET
   */
  const CLIENT_SECRET = 'client_secret';

  /**
   * @const string CODE
   */
  const CODE = 'code';

  /**
   * @const string FORCE - Approval Prompt Option
   */
  const FORCE = 'force';

  /**
   * @const string GRANT_TYPE
   */
  const GRANT_TYPE = 'grant_type';

  /**
   * @const string OFFLINE
   */
  const OFFLINE = 'offline';

  /**
   * @const string ONLINE
   */
  const ONLINE = 'online';

  /**
   * @const string REDIRECT_URL
   */
  const REDIRECT_URL = 'redirect_uri';

  /**
   * @const string REQUEST
   */
  const REQUEST = 'application/x-www-form-urlencoded';

  /**
   * @const string RESPONSE_TYPE
   */
  const RESPONSE_TYPE = 'response_type';

  /**
   * @const string TOKEN
   */
  const TOKEN = 'token';

  /**
   * @const string TYPE
   */
  const TYPE = 'Content-Type';

  /**
   * @var string $accessType
   */
  protected $accessType = self::ONLINE;

  /**
   * @var string $approvalPrompt
   */
  protected $approvalPrompt = self::AUTO;

  /**
   * @var string|null $client client_id
   */
  protected $clientId = null;

  /**
   * @var string|null $clientSecret client_secret
   */
  protected $clientSecret = null;

  /**
   * @var string|null $display
   */
  protected $display = null;

  /**
   * @var string $grantType
   */
  protected $grantType = self::AUTHORIZATION;

  /**
   * @var string $responseType
   */
  protected $responseType = self::CODE;

  /**
   * @var array $scopes
   */
  protected $scopes = [];

  /**
   * @var string|null $state
   */
  protected $state = null;

  /**
   * @var string|null $urlAccess
   */
  protected $urlAccess = null;

  /**
   * @var string|null $urlRedirect
   */
  protected $urlRedirect = null;

  /**
   * @var string|null $urlRequest
   */
  protected $urlRequest = null;

  /**
   * @var string|null $urlResource
   */
  protected $urlResource = null;

  /**
   * @var Closure|null $map The actual curl callback
   */
  protected $map = null;

  /**
   * Set auth to auto approve
   *
   * @return OAuth2
   */
  public function autoApprove(): OAuth2
  {
    $this->approvalPrompt = self::AUTO;
    return $this;
  }

  /**
   * Set auth for force approve
   *
   * @return OAuth2
   */
  public function forceApprove(): OAuth2
  {
    $this->approvalPrompt = self::FORCE;
    return $this;
  }

  /**
   * Set auth for offline access
   *
   * @return OAuth2
   */
  public function forOffline(): OAuth2
  {
    $this->accessType = self::OFFLINE;
    return $this;
  }

  /**
   * Set auth for online access
   *
   * @return OAuth2
   */
  public function forOnline(): OAuth2
  {
    $this->accessType = self::ONLINE;
    return $this;
  }

  /**
   * Returns the access token given the code
   *
   * @param string* $code
   *
   * @return array
   */
  abstract public function getAccessTokens(string $code): array;

  /**
   * Returns the generated login url
   *
   * @return string
   */
  abstract public function getLoginUrl(): string;

  /**
   * Check if the response is json format
   *
   * @param string $string
   *
   * @return bool
   */
  protected function isJson(string $string): bool
  {
    json_decode($string);
    return json_last_error() === JSON_ERROR_NONE;
  }

  /**
   * Set state
   *
   * @param *string $state
   *
   * @return OAuth2
   */
  public function setState(string $state): OAuth2
  {
    $this->state = $state;
    return $this;
  }

  /**
   * Set display
   *
   * @param string|array $display
   *
   * @return OAuth2
   */
  public function setDisplay($display): OAuth2
  {
    $this->display = $display;
    return $this;
  }

  /**
   * Set scope
   *
   * @param string $scope
   *
   * @return OAuth2
   */
  public function setScope(...$scopes): OAuth2
  {
    $this->scopes = $scopes;
    return $this;
  }
}
