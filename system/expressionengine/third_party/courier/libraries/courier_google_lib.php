<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Courier Google library
 *
 * @package courier
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/ee-add-ons/mailing-list-manager
 * @copyright Copyright (c) 2015, BuzzingPixel
 */

class Courier_google_lib
{
	public function __construct()
	{
		// Require the Google API
		require COURIER_PATH . '/lib/google-api/src/Google/autoload.php';

		// Set client variables
		$this->client = new Google_Client();
		$this->client->setApplicationName('Courier');
		$this->client->setScopes(implode(' ', array(
			Google_Service_Gmail::GMAIL_MODIFY
		)));
		$this->client->setAuthConfigFile(
			COURIER_PATH . '/lib/auth_config/client_secret.json'
		);
		$this->client->setAccessType('offline');
	}

	public function requestAuth($listId = false)
	{
		// Make sure list_id is provided
		if (! $listId) {
			return false;
		}

		// Request auth
		$authUrl = $this->client->createAuthUrl();
		$authUrl = str_replace('approval_prompt=auto', 'approval_prompt=force', $authUrl);
		$authUrl .= '&state=' . urlencode('list_id=' . $listId);

		ee()->functions->redirect($authUrl);
	}

	public function setListToken(
		$listId = false,
		$authCode = false,
		$redirect = false
	)
	{
		if (! $listId || ! $authCode || ! $redirect) {
			return false;
		}

		$accessToken = $this->client->authenticate($authCode);

		ee()->courier_model->setListToken($listId, $accessToken);

		ee()->functions->redirect($redirect);
	}

	public function getClient($listId, $accessToken)
	{
		// Load the access token
		$this->client->setAccessToken($accessToken);

		if ($this->client->isAccessTokenExpired()) {
			$this->client->refreshToken($this->client->getRefreshToken());

			ee()->courier_model->setListToken($listId, $accessToken);
		}

		return $this->client;
	}
}