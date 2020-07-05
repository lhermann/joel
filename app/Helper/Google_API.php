<?php

namespace Tonik\Theme\App\Helper;

/*
|-----------------------------------------------------------
| Google API helper class
|-----------------------------------------------------------
*/

use function Tonik\Theme\App\config;
use \Google_Client;
use \Google_Service_YouTube;

class Google_API {
  private $client;

  public function __construct () {
    $credentials_file = get_template_directory().'/'.OAUTH_CREDENTIALS_FILE;
    $callback_url = sprintf(
      '%s/wp-json/%s/v1/google-api-callback',
      WP_SITEURL,
      config('textdomain')
    );

    // Init Client
    $this->client = new Google_Client();
    $this->client->setApplicationName('Joel Video Upload');
    $this->client->setAuthConfig($credentials_file);
    $this->client->setRedirectUri($callback_url);
    $this->client->setScopes(['https://www.googleapis.com/auth/youtube']);
    $this->client->prepareScopes();
    $this->client->setAccessType('offline');
    $this->client->setApprovalPrompt('force');

    // Get token from store
    $token = $this->_getToken();
    if ($token) $this->client->setAccessToken($token);

    // renew token if necessary
    if ($this->client->isAccessTokenExpired()) $this->renewToken();
  }

  public function getToken () {
    return $this->client->getAccessToken();
  }

  public function getClientId () {
    return $this->client->getClientId();
  }

  public function getAuthUrl ($state = null) {
    $state = $state ?: config('textdomain');
    $this->client->setState($state);
    return $this->client->createAuthUrl();
  }

  public function authenticate ($code) {
    $token = $this->client->fetchAccessTokenWithAuthCode($code);
    $token['refresh_token'] = $this->client->getRefreshToken();
    $token = $this->_setToken($token);
    return $token;
  }

  public function authenticated () {
    return !$this->client->isAccessTokenExpired();
  }

  public function renewToken () {
    $token = $this->_getToken();
    if (!$token || !key_exists('refresh_token', $token)) return;
    $new_token = $this->client->fetchAccessTokenWithRefreshToken(
      $token['refresh_token']
    );
    $token = $this->_setToken($new_token);
  }

  public function youtube_api () {
    $this->client->authorize();
    return new Google_Service_YouTube($this->client);
  }

  public function setDefer ($value) {
    $this->client->setDefer($value);
  }

  public function getClientInstance () {
    return $this->client;
  }

  private function _getToken () {
    return get_option('google-api-token');
  }

  private function _setToken ($value) {
    return update_option('google-api-token', $value);
  }
}
