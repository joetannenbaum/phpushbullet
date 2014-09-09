<?php

namespace PHPushbullet;

class PHPushbullet
{
    /**
	 * An instance of the Guzzle client to make requests
	 *
	 * @var \GuzzleHttp\Client $api
	 */

    protected $api;

    /**
	 * The set of devices we are currently pushing to
	 *
	 * @var array $devices
	 */

    protected $devices = [];

    /**
	 * The set of users we are currently pushing to,
	 * an array of emails
	 *
	 * @var array $users
	 */

    protected $users = [];

    /**
	 * An array of all devices available
	 *
	 * @var array $all_devices
	 */

    protected $all_devices = [];

    public function __construct($access_token = null)
    {
        $access_token = $access_token ?: getenv('pushbullet.access_token');

        if (!$access_token) {
            throw new \Exception('Your Pushbullet access token is not set.');
        }

        $this->api = (new Connection($access_token))->client();
    }

    /**
	 * Get a list of all of the devices available
	 *
	 * @return array
	 */

    public function devices()
    {
        if (empty($this->all_devices)) {
            $devices = $this->api->get('devices')->json()['devices'];

            foreach ($devices as $device) {
                $this->all_devices[] = new Device($device);
            }
        }

        return $this->all_devices;
    }

    public function user()
    {
        $this->users = array_merge(func_get_args(), $this->users);
        $this->users = array_filter($this->users);
        $this->users = array_unique($this->users);

        return $this;
    }

    /**
	 * Set the passed in device(s) for the current push
	 *
	 * @return PHPushbullet\PHPushbullet
	 */

    public function device()
    {
        foreach (func_get_args() as $destination) {
            $device = $this->getDeviceIden($destination);

            if (!$device) {
                throw new \Exception("{$destination} is not a valid device.");
            }

            $this->devices[] = $device;
        }

        return $this;
    }

    /**
	 * Set all of the devices for the current push
	 *
	 * @return PHPushbullet\PHPushbullet
	 */

    public function all()
    {
        foreach ($this->devices() as $device) {
            $this->devices[] = $device->iden;
        }

        return $this;
    }

    /**
	 * Actually send the push
	 *
	 * @return array
	 */

    public function push($request)
    {
        if (empty($this->devices) && empty($this->users)) {
            throw new \Exception('You must specify which either a device or user to push to.');
        }

        $responses = [];

        foreach ($this->devices as $device) {
            $responses[] = $this->pushRequest($request, ['device_iden' => $device]);
        }

        foreach ($this->users as $user) {
            $responses[] = $this->pushRequest($request, ['email' => $user]);
        }

        $this->devices = [];
        $this->users   = [];

        return $responses;
    }

    /**
	 * Create push request and... push it
	 *
	 * @param array $request
	 * @param array $merge
	 * @return array
	 */

    protected function pushRequest($request, $merge)
    {
        $request  = array_merge($request, $merge);
        $response = $this->api->post('pushes', ['json' => $request]);

        return $response->json();
    }

    /**
	 * Get the `iden` for the device by either the iden or nickname
	 *
	 * @param string $device
	 * @return mixed (boolean|string)
	 */

    protected function getDeviceIden($device)
    {
        foreach ($this->devices() as $available_device) {
            foreach (['iden', 'nickname'] as $field) {
                if ($available_device->$field == $device) {
                    return $available_device->iden;
                }
            }
        }

        return FALSE;
    }

    /**
	 * Magic method, figures out what sort of push the user is trying to do
	 */

    public function __call($method, $arguments)
    {
        $request_class = 'PHPushbullet\Request\Push' . ucwords($method);

        if (class_exists($request_class)) {
            $class   = new \ReflectionClass($request_class);
            $request = $class->newInstanceArgs($arguments);

            return $this->push($request->request());
        }

        throw new \Exception('Unknown method "' . $method . '"');
    }

}
