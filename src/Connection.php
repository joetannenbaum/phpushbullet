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

    public function __construct($access_token, Client $client = null, array $config = [])
    {
        $this->client = $client ?: new Client($this->getClientParams($access_token, $config));
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function client()
    {
        return $this->client;
    }

    protected function getClientParams($access_token, array $config = [])
    {
        $headers = [
            'Access-Token' => $access_token,
            'Content-Type' => 'application/json',
        ];

        $config = array_merge(compact('headers'), $config);

        if (version_compare(Client::VERSION, 6, '>=')) {
            return array_merge([
                'base_uri' => $this->base_url,
            ], $config);
        }

        return [
            'base_url' => [$this->base_url, []],
            'defaults' => $config,
        ];
    }
}
