<?php

require_once 'base/PHPushbulletTestBase.php';

class NoteTest extends PHPushbulletTestBase
{
    /** @test */

    public function it_can_push_a_note()
    {
        $devices = [
            'devices' => [
                $this->getDevice('android'),
                $this->getDevice('chrome')
            ],
        ];

        $response = $this->pushResponse([
          'type'  => 'note',
          'title' => 'Reminder',
          'body'  => 'Do this thing',
        ]);

        $this->mock([
            $this->okResponse($devices),
            $this->okResponse($response),
        ]);

        $response = $this->pushbullet->device('Chrome')->note('Reminder', 'Do this thing');

        $this->assertInternalType('array', $response);
        $this->assertCount(1, $response);

        $first = reset($response);

        $this->assertSame('note', $first['type']);
        $this->assertSame('Reminder', $first['title']);
        $this->assertSame('Do this thing', $first['body']);

        $this->assertRequestHistory(['devices', 'pushes']);
    }
}
