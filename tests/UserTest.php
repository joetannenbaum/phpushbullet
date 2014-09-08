<?php

require_once 'base/PHPushbulletTestBase.php';

class UserTest extends PHPushbulletTestBase
{
    /** @test */

    public function it_can_send_to_a_user()
    {

        $response = $this->pushResponse([
                                          'type'  => 'note',
                                          'title' => 'Reminder',
                                          'body'  => 'Do this thing',
                                        ]);

        $this->mock([
            $this->okResponse( $response ),
        ]);

        $response = $this->pushbullet->user('test@joe.codes')->note('Title', 'Body');

        $this->assertInternalType( 'array', $response );
        $this->assertCount( 1, $response );

        $expected_flow = [ 'pushes' ];
        $actual_flow   = $this->getFlow();

        $this->assertSame( $expected_flow, $actual_flow );
    }

}