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

        $expected_flow = [ 'pushes' ];
        $actual_flow   = $this->getFlow();

        $this->assertSame($expected_flow, $actual_flow);
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

        $expected_flow = [ 'pushes', 'pushes' ];
        $actual_flow   = $this->getFlow();

        $this->assertSame($expected_flow, $actual_flow);
    }
}
