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
    $this->client->setScopes([YouTube::YOUTUBE, YouTube::YOUTUBE_FORCE_SSL]);
    $this->client->prepareScopes();
    $this->client->setAccessType('offline');
    $this->client->setPrompt('consent');

    // Get token from store and renew if expired
    $token = $this->_getToken();
    if ($token) {
      $this->client->setAccessToken($token);
      if ($this->client->isAccessTokenExpired()) {
        $this->renewToken();
      }
    }
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
    error_log('[Google_API] authenticate: keys=' . implode(',', array_keys($token))
      . ' refresh_token=' . (empty($token['refresh_token']) ? 'MISSING' : 'present')
      . ' error=' . ($token['error'] ?? 'none'));

    // refresh_token is already in $token if access_type=offline
    // only fall back to getRefreshToken() if missing
    if (empty($token['refresh_token'])) {
      $token['refresh_token'] = $this->client->getRefreshToken();
      error_log('[Google_API] authenticate: used getRefreshToken() fallback, result=' . (empty($token['refresh_token']) ? 'MISSING' : 'present'));
    }

    $this->_setToken($token);
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
        error_log('[Google_API] renewToken error: ' . $new_token['error']);
        if ($new_token['error'] === 'invalid_grant') {
          // Refresh token revoked or expired — force re-auth
          delete_option('google-api-token');
        }
        return false;
      }
      // Preserve refresh_token (Google doesn't return it on renewal)
      if (empty($new_token['refresh_token'])) {
        $new_token['refresh_token'] = $token['refresh_token'];
      }
      $this->_setToken($new_token);
      return true;
    } catch (\Exception $e) {
      error_log('[Google_API] renewToken exception: ' . $e->getMessage());
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
