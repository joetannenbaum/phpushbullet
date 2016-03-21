<?php

require_once 'base/PHPushbulletTestBase.php';

class LinkTest extends PHPushbulletTestBase
{
    /** @test */

    public function it_can_push_a_link()
    {
        $devices = [
            'devices' => [
                $this->getDevice('android'),
                $this->getDevice('chrome')
            ],
        ];

        $response = $this->pushResponse([
          'type'  => 'link',
          'title' => 'Google',
          'url'   => 'http://www.google.com',
          'body'  => 'Search it',
        ]);

        $this->mock([
            $this->okResponse($devices),
            $this->okResponse($response),
        ]);

        $response = $this->pushbullet->device('Chrome')->link('Google', 'http://www.google.com', 'Search it');

        $this->assertInternalType('array', $response);
        $this->assertCount(1, $response);

        $first = reset($response);

        $this->assertSame('link', $first['type']);
        $this->assertSame('Google', $first['title']);
        $this->assertSame('http://www.google.com', $first['url']);
        $this->assertSame('Search it', $first['body']);

        $this->assertRequestHistory(['devices', 'pushes']);
    }

    /** @test */

    public function it_can_push_a_link_without_a_body()
    {
        $devices = [
            'devices' => [
                $this->getDevice('android'),
                $this->getDevice('chrome')
            ],
        ];

        $response = $this->pushResponse([
          'type'  => 'link',
          'title' => 'Google',
          'url'   => 'http://www.google.com',
        ]);

        $this->mock([
            $this->okResponse($devices),
            $this->okResponse($response),
        ]);

        $response = $this->pushbullet->device('Chrome')->link('Google', 'http://www.google.com');

        $this->assertInternalType('array', $response);
        $this->assertCount(1, $response);

        $first = reset($response);

        $this->assertSame('link', $first['type']);
        $this->assertSame('Google', $first['title']);
        $this->assertSame('http://www.google.com', $first['url']);

        $this->assertRequestHistory(['devices', 'pushes']);
    }
}
