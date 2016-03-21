<?php

require_once 'base/PHPushbulletTestBase.php';

class ListTest extends PHPushbulletTestBase
{
    /** @test */

    public function it_can_push_a_list()
    {
        $devices = [
            'devices' => [
                $this->getDevice('android'),
                $this->getDevice('chrome')
            ],
        ];

        $response = $this->pushResponse([
          'type'  => 'list',
          'title' => 'My List',
          'items' => [
            'This',
            'That',
            'Other thing',
          ],
        ]);

        $this->mock([
            $this->okResponse($devices),
            $this->okResponse($response),
        ]);

        $response = $this->pushbullet->device('Chrome')->list('My List', [
            'This',
            'That',
            'Other thing',
        ]);

        $this->assertInternalType('array', $response);
        $this->assertCount(1, $response);

        $first = reset($response);

        $this->assertSame('list', $first['type']);
        $this->assertSame('My List', $first['title']);
        $this->assertSame([
                            'This',
                            'That',
                            'Other thing',
                          ], $first['items']);

        $this->assertRequestHistory(['devices', 'pushes']);
    }
}
