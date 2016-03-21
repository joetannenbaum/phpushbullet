<?php

require_once 'base/PHPushbulletTestBase.php';

class ChannelTest extends PHPushbulletTestBase
{
    /** @test */

    public function it_can_chain_channels()
    {
        $response = $this->pushResponse([
                                          'type'  => 'note',
                                          'title' => 'Reminder',
                                          'body'  => 'Do this thing',
                                        ]);

        $this->mock([
            $this->okResponse($response),
            $this->okResponse($response),
        ]);

        $response = $this->pushbullet->channel('mychanneltag')->note('Title', 'Body');

        $this->assertInternalType('array', $response);
        $this->assertCount(1, $response);

        $this->assertRequestHistory(['pushes']);
    }

    /** @test */

    public function it_can_pass_multiple_channels()
    {
        $response = $this->pushResponse([
                                          'type'  => 'note',
                                          'title' => 'Reminder',
                                          'body'  => 'Do this thing',
                                        ]);

        $this->mock([
            $this->okResponse($response),
            $this->okResponse($response),
        ]);

        $response = $this->pushbullet->channel('mychanneltag', 'anotherchanneltag')->note('Title', 'Body');

        $this->assertInternalType('array', $response);
        $this->assertCount(2, $response);

        $this->assertRequestHistory(['pushes', 'pushes']);
    }
}
