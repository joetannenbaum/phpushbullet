<?php

use GuzzleHttp\Client;
use PHPushbullet\Connection;
use PHPushbullet\PHPushbullet;

class PHPushbulletTestBase extends PHPUnit_Framework_TestCase
{
    protected $pushbullet;

    protected $client;

    protected $history;

    protected $guzzle_6;

    protected $mock_handler;

    public function setUp()
    {
        $this->guzzle_6 = version_compare(Client::VERSION, 6, '>=');

        if ($this->guzzle_6) {
            $this->mock_handler = new \GuzzleHttp\Handler\MockHandler();
            $this->history      = [];

            $history = \GuzzleHttp\Middleware::history($this->history);
            $stack   = \GuzzleHttp\HandlerStack::create($this->mock_handler);
            $stack->push($history);
            $client  = new Client(['handler' => $stack]);
        } else {
            $client        = new Client();
            $this->history = new \GuzzleHttp\Subscriber\History();
            $this->mock_handler = new \GuzzleHttp\Subscriber\Mock();

            $client->getEmitter()->attach($this->history);
            $client->getEmitter()->attach($this->mock_handler);
        }

        $connection = new Connection('random', $client);

        $this->pushbullet = new PHPushbullet('random', $connection);
    }

    protected function okResponse($body)
    {
        $body = json_encode($body);

        if ($this->guzzle_6) {
            return new \GuzzleHttp\Psr7\Response(200, ['Content-Length' => strlen($body)], $body);
        }

        $response = [
            'HTTP/1.1 200 OK',
            'Content-Length: ' . strlen($body),
            '',
            $body,
        ];

        return implode("\r\n", $response);
    }

    protected function mock(array $mocks)
    {
        foreach ($mocks as $mock) {
            if ($this->guzzle_6) {
                $this->mock_handler->append($mock);
            } else {
                $this->mock_handler->addResponse($mock);
            }
        }
    }

    protected function pushResponse($vars)
    {
        $standard = [
            'iden'                      => 'ubdpjxxxOK0sKG',
            'created'                   => 1399253701.9746201,
            'modified'                  => 1399253701.9744401,
            'active'                    => true,
            'dismissed'                 => false,
            'owner_iden'                => 'ubd',
            'target_device_iden'        => 'ubddjAy95rgBxc',
            'sender_iden'               => 'ubd',
            'sender_email'              => 'ryan@pushbullet.com',
            'sender_email_normalized'   => 'ryan@pushbullet.com',
            'receiver_iden'             => 'ubd',
            'receiver_email'            => 'ryan@pushbullet.com',
            'receiver_email_normalized' => 'ryan@pushbullet.com'
        ];

        return array_merge($vars, $standard);
    }

    protected function getDevice($type)
    {
        $devices = [
            'android' => [
                'iden'         => 'u1qSJddxeKwOGuGW',
                'push_token'   => 'u1qSJddxeKwOGuGWu1qdxeKwOGuGWu1qSJddxeK',
                'app_version'  => 74,
                'fingerprint'  => '{"mac_address":"57:2e:5c:38:39:81","android_id":"ao2cdb9gmf485e96"}',
                'active'       => true,
                'nickname'     => 'Galaxy S4',
                'manufacturer' => 'samsung',
                'type'         => 'android',
                'created'      => 1394748080.0139201,
                'modified'     => 1399008037.8487799,
                'model'        => 'SCH-I545',
                'pushable'     => true
            ],
            'chrome' => [
                'iden'         => 'u1qSJddxeKwOGuG1',
                'push_token'   => 'u1qSJddxeKwOGuGWu1qdxeKwOGuGWu1qSJddxe3',
                'app_version'  => 74,
                'fingerprint'  => '{"mac_address":"57:2e:5c:38:39:81","android_id":"ao2cdb9gmf485e96"}',
                'active'       => true,
                'nickname'     => 'Chrome',
                'manufacturer' => 'google',
                'type'         => 'chrome',
                'created'      => 1394748080.0139201,
                'modified'     => 1399008037.8487799,
                'model'        => 'SCH-I745',
                'pushable'     => true
            ]
        ];

        return $devices[$type];
    }

    protected function getRuquestUrls()
    {
        if ($this->guzzle_6) {
            return array_map(function ($request) {
                return (string) $request['request']->getUri();
            }, $this->history);
        }

        return array_map(function ($request) {
            return $request->getUrl();
        }, $this->history->getRequests());
    }

    protected function assertRequestHistory(array $expected)
    {
        $this->assertSame($expected, $this->getRuquestUrls());
    }

    /** @test **/

    public function nothing()
    {
    }
}
