<?php

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Subscriber\History;
use PHPushbullet\PHPushbullet;

class PHPushbulletTestBase extends PHPUnit_Framework_TestCase {

    protected $pushbullet;

    protected $client;

    protected $history;

    public function setUp()
    {
        $reflected = new ReflectionClass('PHPushbullet\PHPushbullet');

        $this->history = new History();

        $this->client = $reflected->getProperty('api');
        $this->client->setAccessible( TRUE );
        $this->pushbullet = new PHPushbullet('random');
        $this->client->setValue( $this->pushbullet, new Client() );
        $this->client->getValue( $this->pushbullet )->getEmitter()->attach( $this->history );
    }

    protected function okResponse( $body )
    {
        $body = json_encode( $body );

        $response = [
            'HTTP/1.1 200 OK',
            'Content-Length: ' . strlen( $body ),
            '',
            $body,
        ];

        return implode( "\r\n", $response );
    }

    protected function mock( $mock )
    {
        $mock = new Mock( $mock );

        $this->client->getValue( $this->pushbullet )->getEmitter()->attach( $mock );
    }

    protected function pushResponse( $vars )
    {
        $standard = [
                      'iden'                      => 'ubdpjxxxOK0sKG',
                      'created'                   => 1399253701.9746201,
                      'modified'                  => 1399253701.9744401,
                      'active'                    => TRUE,
                      'dismissed'                 => FALSE,
                      'owner_iden'                => 'ubd',
                      'target_device_iden'        => 'ubddjAy95rgBxc',
                      'sender_iden'               => 'ubd',
                      'sender_email'              => 'ryan@pushbullet.com',
                      'sender_email_normalized'   => 'ryan@pushbullet.com',
                      'receiver_iden'             => 'ubd',
                      'receiver_email'            => 'ryan@pushbullet.com',
                      'receiver_email_normalized' => 'ryan@pushbullet.com'
                    ];

        return array_merge( $vars, $standard );
    }

    protected function getDevice( $type )
    {

        $devices = [
            'android' => [
                'iden'         => 'u1qSJddxeKwOGuGW',
                'push_token'   => 'u1qSJddxeKwOGuGWu1qdxeKwOGuGWu1qSJddxeK',
                'app_version'  => 74,
                'fingerprint'  => '{"mac_address":"57:2e:5c:38:39:81","android_id":"ao2cdb9gmf485e96"}',
                'active'       => TRUE,
                'nickname'     => 'Galaxy S4',
                'manufacturer' => 'samsung',
                'type'         => 'android',
                'created'      => 1394748080.0139201,
                'modified'     => 1399008037.8487799,
                'model'        => 'SCH-I545',
                'pushable'     => TRUE
            ],
            'chrome' => [
                'iden'         => 'u1qSJddxeKwOGuG1',
                'push_token'   => 'u1qSJddxeKwOGuGWu1qdxeKwOGuGWu1qSJddxe3',
                'app_version'  => 74,
                'fingerprint'  => '{"mac_address":"57:2e:5c:38:39:81","android_id":"ao2cdb9gmf485e96"}',
                'active'       => TRUE,
                'nickname'     => 'Chrome',
                'manufacturer' => 'google',
                'type'         => 'chrome',
                'created'      => 1394748080.0139201,
                'modified'     => 1399008037.8487799,
                'model'        => 'SCH-I745',
                'pushable'     => TRUE
            ]
        ];

        return $devices[ $type ];
    }

    protected function getFlow()
    {
        $flow = [];

        foreach ($this->history as $transaction) {
            $flow[] = $transaction['request']->getUrl();
        }

        return $flow;
    }

    /** @test **/

    public function nothing()
    {

    }

}