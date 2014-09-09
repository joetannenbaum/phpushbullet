<?php

require_once 'base/PHPushbulletTestBase.php';

class DeviceTest extends PHPushbulletTestBase
{
    /** @test */

    public function it_can_list_available_devices()
    {
        $response = [
                        'devices' => [
                            $this->getDevice('android')
                        ]
                    ];

        $this->mock([
            $this->okResponse( $response )
        ]);

        $response = $this->pushbullet->devices();

        $this->assertInternalType( 'array', $response );
        $this->assertCount( 1, $response );
        $this->assertInternalType( 'object', reset( $response ) );
        $this->assertInstanceOf( 'PHPushbullet\Device', reset( $response ) );

        $expected_flow = [ 'devices' ];
        $actual_flow   = $this->getFlow();

        $this->assertSame( $expected_flow, $actual_flow );
    }

    /** @test */

    public function it_can_chain_devices()
    {
        $devices = [
                        'devices' => [
                            $this->getDevice('android'),
                            $this->getDevice('chrome'),
                        ]
                    ];



        $response = $this->pushResponse([
                                          'type'  => 'note',
                                          'title' => 'Reminder',
                                          'body'  => 'Do this thing',
                                        ]);

        $this->mock([
            $this->okResponse( $devices ),
            $this->okResponse( $response ),
            $this->okResponse( $response ),
        ]);

        $response = $this->pushbullet->device('Galaxy S4')->device('Chrome')->note('Title', 'Body');

        $this->assertInternalType( 'array', $response );
        $this->assertCount( 2, $response );

        $expected_flow = [ 'devices', 'pushes', 'pushes' ];
        $actual_flow   = $this->getFlow();

        $this->assertSame( $expected_flow, $actual_flow );
    }

    /** @test */

    public function it_can_pass_multiple_devices()
    {
        $devices = [
                        'devices' => [
                            $this->getDevice('android'),
                            $this->getDevice('chrome'),
                        ]
                    ];



        $response = $this->pushResponse([
                                          'type'  => 'note',
                                          'title' => 'Reminder',
                                          'body'  => 'Do this thing',
                                        ]);

        $this->mock([
            $this->okResponse( $devices ),
            $this->okResponse( $response ),
            $this->okResponse( $response ),
        ]);

        $response = $this->pushbullet->device('Galaxy S4', 'Chrome')->note('Title', 'Body');

        $this->assertInternalType( 'array', $response );
        $this->assertCount( 2, $response );

        $expected_flow = [ 'devices', 'pushes', 'pushes' ];
        $actual_flow   = $this->getFlow();

        $this->assertSame( $expected_flow, $actual_flow );
    }

    /**
     * @test
     * @expectedException        Exception
     * @expectedExceptionMessage You must specify which either a device or user to push to.
     */

    public function it_throws_an_error_for_if_a_device_is_not_set()
    {
        $response = $this->pushbullet->note('Title', 'Body');
    }

    /**
     * @test
     * @expectedException        Exception
     * @expectedExceptionMessage Chromebook is not a valid device.
     */

    public function it_throws_an_error_for_an_invalid_device()
    {
        $response = [
                        'devices' => [
                            $this->getDevice('android')
                        ]
                    ];

        $this->mock([
            $this->okResponse( $response )
        ]);

        $response = $this->pushbullet->device('Chromebook')->note('Title', 'Body');
    }

}