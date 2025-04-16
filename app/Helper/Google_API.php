<?php

namespace Tonik\Theme\App\Helper;
use Google\Client;
use Google\Service\YouTube;

/*
|-----------------------------------------------------------
| Google API helper class
|-----------------------------------------------------------
*/

use function Tonik\Theme\App\config;

class Google_API {
  private $client;

  public function __construct () {
    $callback_url = sprintf(
      '%s/wp-json/%s/v1/google-api-callback',
      get_bloginfo('url'),
      config('textdomain')
    );

    // Init Client
    $this->client = new Client();
    $this->client->setApplicationName('Joel Video Upload');
    $this->client->setAuthConfig(OAUTH_CREDENTIALS_FILE);
    $this->client->setRedirectUri($callback_url);
    $this->client->setScopes([YouTube::YOUTUBE]);
    $this->client->prepareScopes();
    $this->client->setAccessType('offline');
    $this->client->setApprovalPrompt('force');

    // Get token from store
    $token = $this->_getToken();
    if ($token) $this->client->setAccessToken($token);

    // Get token from store
    $token = $this->_getToken();
    if ($token) {
      $this->client->setAccessToken($token);
      // Attempt to renew if expired
      if ($this->client->isAccessTokenExpired()) {
        if (!$this->renewToken()) {
          // Token renewal failed, clear the token
          $this->_setToken(null);
        }
      }
    }

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

  public function authenticated() {
    $token = $this->_getToken();
    if (!$token) {
      return false;
    }
    if ($this->client->isAccessTokenExpired()) {
      return $this->renewToken();
    }
    return true;
  }

  public function renewToken() {
    $token = $this->_getToken();
    if (!$token || !isset($token['refresh_token'])) {
      // No valid token available, user needs to re-authenticate
      return false;
    }
    try {
      $new_token = $this->client->fetchAccessTokenWithRefreshToken($token['refresh_token']);
      if (isset($new_token['error'])) {
        // Refresh token is invalid, remove the stored token
        delete_option('google-api-token');
        return false;
      }
      $this->_setToken($new_token);
      return true;
    } catch (Exception $e) {
      // Handle any exceptions (e.g., network errors)
      error_log('Error refreshing Google API token: ' . $e->getMessage());
      return false;
    }
  }

  public function youtube_api () {
    $this->client->authorize();
    return new Google\Service\YouTube($this->client);
  }

  public function setDefer ($value) {
    $this->client->setDefer($value);
  }

  public function getClientInstance () {
    return $this->client;
  }

  private function _getToken() {
    $token = get_option('google-api-token');
    if (is_array($token) && isset($token['error'])) {
      // Token is invalid, remove it
      delete_option('google-api-token');
      return null;
    }
    return $token;
  }

  private function _setToken ($value) {
    return update_option('google-api-token', $value);
  }
}
