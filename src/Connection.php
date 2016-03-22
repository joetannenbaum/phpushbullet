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

    public function __construct($access_token, Client $client = null)
    {
        $this->client = $client ?: new Client($this->getClientParams($access_token));
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function client()
    {
        return $this->client;
    }

    protected function getClientParams($access_token)
    {
        $headers = [
            'Access-Token' => $access_token,
            'Content-Type' => 'application/json',
        ];

        if (version_compare(Client::VERSION, 6, '>=')) {
            return [
                'base_uri' => $this->base_url,
                'headers'  => $headers,
            ];
        }

        return [
            'base_url' => [$this->base_url, []],
            'defaults' => ['headers'  => $headers],
        ];
    }
}
