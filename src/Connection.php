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
        $this->client = new Client($this->getClientParams($access_token));
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
        if (substr(Client::VERSION, 0, 1) == 6) {
            return [
                'base_uri' => $this->base_url,
                'headers'  => [
                    'Access-Token' => $access_token,
                    'Content-Type' => 'application/json',
                ],
            ];
        }

        return [
            'base_url' => [$this->base_url, []],
            'defaults' => [
                'headers'  => [
                    'Access-Token' => $access_token,
                    'Content-Type' => 'application/json',
                ],
            ],
        ];
    }
}
