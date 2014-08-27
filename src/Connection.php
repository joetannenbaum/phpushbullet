<?php

namespace JoeTannenbaum\PHPushbullet;

class Connection {

	/**
	 * The base url for the Pushbullet API
	 *
	 * @var string $base_url
	 */

	protected $base_url = 'https://api.pushbullet.com/{version}/';

	/**
	 * The version of the Pushbullet API
	 *
	 * @var string $version
	 */

	protected $version  = 'v2';

	protected $client;

	/**
	 * Get the Guzzle client with defaults
	 *
	 * @return \GuzzleHttp\Client
	 */

	public function __construct( $access_token )
	{
		$this->client = new \GuzzleHttp\Client([
									'base_url' => [
													$this->base_url,
													[ 'version' => $this->version ],
												],
									'defaults' => [
									    'auth' => [ $access_token, '' ],
									],
								]);
	}

	public function client()
	{
		return $this->client;
	}
}