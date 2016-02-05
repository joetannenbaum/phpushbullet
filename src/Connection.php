<?php

namespace PHPushbullet;

use GuzzleHttp\Client;

class Connection
{
    /**
     * The base url for the Pushbullet API
     *
     * @var string $base_url
     */
    protected $base_url = 'https://api.pushbullet.com/v2/';


    /**
     * The pre-configured Guzzle client
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    public function __construct($access_token)
    {
        $this->client = new Client([
            'base_url' => [$this->base_url, []],
            'defaults' => [
                'auth' => [$access_token, ''],
            ],
        ]);
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function client()
    {
        return $this->client;
    }
}
