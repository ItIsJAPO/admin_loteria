<?php

namespace util\socialmedia;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

use plataforma\exception\IntentionalException;

use util\TwitterOAuth\TwitterOAuthException;
use util\TwitterOAuth\TwitterOAuth;

use Facebook\Facebook;

class SocialMedia {

	public static function findDataFacebookUser( $token ) {
		$facebook = new Facebook();

		try {

		  	$response = $facebook->get('/me?fields=email,id,name,picture', $token);

		} catch ( FacebookResponseException $e ) {
		  	throw new IntentionalException(0, $e->getMessage());
		} catch ( FacebookSDKException $e ) {
		  	throw new IntentionalException(0, $e->getMessage());
		}

		return $response->getGraphUser();
	}

	public static function createUrlAuthorizeTwitter( $oauth_token ) {
		$connection = new TwitterOAuth();

		$url = '#';

		try {

			$url = $connection->url('oauth/authorize', array('oauth_token' => $oauth_token));

		} catch ( TwitterOAuthException $e ) {}

		return $url;
	}

	public static function createUrlAuthenticateTwitter( $oauth_token ) {
		$connection = new TwitterOAuth();

		$url = '#';

		try {

			$url = $connection->url('oauth/authenticate', array('oauth_token' => $oauth_token));

		} catch ( TwitterOAuthException $e ) {}

		return $url;
	}

	public static function createRequestTokenTwitter( $oauth_callback ) {
		$connection = new TwitterOAuth();

		$request_token = NULL;

		try {

			$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => $oauth_callback));

		} catch ( TwitterOAuthException $e ) {}

		return $request_token;
	}

	public static function findDataTwitterUser( $oauth_token, $oauth_token_secret, $oauth_verifier  ) {
		$connection = new TwitterOAuth($oauth_token, $oauth_token_secret);

		$access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $oauth_verifier]);

		$connection = new TwitterOAuth($access_token['oauth_token'], $access_token['oauth_token_secret']);

		$user = $connection->get("account/verify_credentials", array("include_email" => 'true', 'include_entities' => 'false', 'skip_status' => true));

		return array(
			'user' => $user,
			'access_token' => $access_token
		);
	}

	public static function findDataGoogleUser( $token ) {
		require_once 'util/google-api-php-client-2.2.1/vendor/autoload.php';

		$client = new \Google_Client();

		$info_user_data = $client->verifyIdToken($token);
		
		if ( empty($info_user_data) ) {
			throw new IntentionalException(IntentionalException::GOOGLE_USER_DATA_NOT_FOUND);
		}

		return $info_user_data;
	}
}