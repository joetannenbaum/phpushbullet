<?php

namespace JoeTannenbaum\PHPushbullet;

class PHPushbullet {

	/**
	 * An instance of the Guzzle client to make requests
	 *
	 * @var \GuzzleHttp\Client $client
	 */

	protected $client;

	/**
	 * The Pushbullet access token
	 *
	 * @var string $access_token
	 */

	protected $access_token;

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

	/**
	 * The set of devices we are currently pushing to
	 *
	 * @var array $devices
	 */

	protected $devices = [];

	/**
	 * An array of all devices available
	 *
	 * @var array $all_devices
	 */

	protected $all_devices = [];

	public function __construct()
	{
		$this->access_token = getenv( 'pushbullet.access_token' );

		if ( !$this->access_token )
		{
			throw new \Exception('Your Pushbullet access token is not present in your .env file.');
		}

		$this->client = $this->getClient();
	}

	/**
	 * Get a list of all of the devices available
	 *
	 * @return array
	 */

	public function devices()
	{
		if ( empty( $this->all_devices ) )
		{
			$devices = $this->client->get('devices')->json()['devices'];

			foreach ( $devices as $device )
			{
				$this->all_devices[] = new Device( $device );
			}
		}

		return $this->all_devices;
	}

	/**
	 * Set all of the devices for the current push
	 *
	 * @return JoeTannenbaum\PHPushbullet\PHPushbullet
	 */

	public function all()
	{
		foreach ( $this->devices() as $available_device )
		{
			$this->device[] = $available_device->iden;
		}

		return $this;
	}

	/**
	 * Set the passed in device(s) for the current push
	 *
	 * @return JoeTannenbaum\PHPushbullet\PHPushbullet
	 */

	public function device()
	{
		foreach ( func_get_args() as $destination )
		{
			$device = $this->getDeviceIden( $destination );

			if ( !$device )
			{
				throw new \Exception( "{$destination_device} is not a valid device." );
			}

			$this->devices[] = $device;
		}

		return $this;
	}

	/**
	 * Actually send the push
	 *
	 * @return array
	 */

	public function push( $request )
	{
		$responses = [];

		foreach ( $this->devices as $device )
		{
			$request['device_iden'] = $device;
			$response               = $this->client->post('pushes', [ 'json' => $request ]);
			$responses[]            = $response->json();
		}

		$this->devices = [];

		return $responses;
	}

	/**
	 * Get the `iden` for the device by either the iden or nickname
	 *
	 * @param string $device
	 * @return mixed (boolean|string)
	 */

	protected function getDeviceIden( $device )
	{
		foreach ( $this->devices() as $available_device )
		{
			foreach ( [ 'iden', 'nickname'] as $field )
			{
				if ( $available_device->$field == $device )
				{
					return $available_device->iden;
				}
			}
		}

		return FALSE;
	}

	/**
	 * Get the Guzzle client with defaults
	 *
	 * @return \GuzzleHttp\Client
	 */

	protected function getClient()
	{
		return new \GuzzleHttp\Client([
									'base_url' => [
													$this->base_url,
													[ 'version' => $this->version ],
												],
									'defaults' => [
									    'auth' => [ $this->access_token, '' ],
									],
								]);
	}

	/**
	 * Magic method, figures out what sort of push the user is trying to do
	 */

	public function __call( $method, $arguments )
	{
		$request_class = 'JoeTannenbaum\\PHPushbullet\\Request\\Push' . ucwords( $method );

		if ( class_exists( $request_class ) )
		{
			$class   = new \ReflectionClass( $request_class );
			$request = $class->newInstanceArgs( $arguments );

			return $this->push( $request->request() );
		}

		throw new \Exception( 'Unknown method "' . $method . '"' );
	}

}