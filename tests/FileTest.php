<?php

require_once 'base/PHPushbulletTestBase.php';

class FileTest extends PHPushbulletTestBase
{
    /** @test */

    public function it_can_push_a_file()
    {
        $devices = [
            'devices' => [
                $this->getDevice('android'),
                $this->getDevice('chrome')
            ],
        ];

        $response = $this->pushResponse([
            'type'      => 'file',
            'file_name' => 'Kitten',
            'file_url'  => 'http://placehold.it/350x150',
            'file_type' => 'image/jpeg',
            'body'      => 'Just a baby cat.',
        ]);

        $this->mock([
            $this->okResponse($devices),
            $this->okResponse($response),
        ]);

        $response = $this->pushbullet->device('Chrome')->file('Kitten', 'http://placehold.it/350x150', 'Just a baby cat.');

        $this->assertInternalType('array', $response);
        $this->assertCount(1, $response);

        $first = reset($response);

        $this->assertSame('file', $first['type']);
        $this->assertSame('Kitten', $first['file_name']);
        $this->assertSame('http://placehold.it/350x150', $first['file_url']);
        $this->assertSame('image/jpeg', $first['file_type']);
        $this->assertSame('Just a baby cat.', $first['body']);

        $this->assertRequestHistory(['devices', 'pushes']);
    }

    /** @test */

    public function it_can_push_a_file_without_a_body()
    {
        $devices = [
            'devices' => [
                $this->getDevice('android'),
                $this->getDevice('chrome')
            ],
        ];

        $response = $this->pushResponse([
            'type'      => 'file',
            'file_name' => 'Kitten',
            'file_url'  => 'http://placehold.it/350x150',
            'file_type' => 'image/jpeg',
        ]);

        $this->mock([
            $this->okResponse($devices),
            $this->okResponse($response),
        ]);

        $response = $this->pushbullet->device('Chrome')->file('Kitten', 'http://placehold.it/350x150');

        $this->assertInternalType('array', $response);
        $this->assertCount(1, $response);

        $first = reset($response);

        $this->assertSame('file', $first['type']);
        $this->assertSame('Kitten', $first['file_name']);
        $this->assertSame('http://placehold.it/350x150', $first['file_url']);
        $this->assertSame('image/jpeg', $first['file_type']);

        $this->assertRequestHistory(['devices', 'pushes']);
    }
}
