<?php

require_once 'base/PHPushbulletTestBase.php';

class AddressTest extends PHPushbulletTestBase
{
    /** @test */

    public function it_can_push_an_address_as_a_string()
    {
        $devices = [
            'devices' => [
                $this->getDevice('android'),
                $this->getDevice('chrome')
            ],
        ];

        $response = $this->pushResponse([
                                          'type'    => 'address',
                                          'name'    => 'Home',
                                          'address' => '123 Sesame Street',
                                        ]);

        $this->mock([
            $this->okResponse($devices),
            $this->okResponse($response),
        ]);

        $response = $this->pushbullet->device('Chrome')->address('Home', '123 Sesame Street');

        $this->assertInternalType('array', $response);
        $this->assertCount(1, $response);

        $first = reset($response);

        $this->assertSame('address', $first['type']);
        $this->assertSame('Home', $first['name']);
        $this->assertSame('123 Sesame Street', $first['address']);

        $this->assertRequestHistory(['devices', 'pushes']);
    }

    /** @test */

    public function it_can_push_an_address_as_an_array()
    {
        $devices = [
            'devices' => [
                $this->getDevice('android'),
                $this->getDevice('chrome')
            ],
        ];

        $response = $this->pushResponse([
                                          'type'    => 'address',
                                          'name'    => 'Home',
                                          'address' => '123 Sesame Street New York NY 10001',
                                        ]);

        $this->mock([
            $this->okResponse($devices),
            $this->okResponse($response),
        ]);

        $address = [
            'address' => '123 Sesame Street',
            'city'    => 'New York',
            'state'   => 'NY',
            'zip'     => 10001,
        ];

        $response = $this->pushbullet->device('Chrome')->address('Home', $address);

        $this->assertInternalType('array', $response);
        $this->assertCount(1, $response);

        $first = reset($response);

        $this->assertSame('address', $first['type']);
        $this->assertSame('Home', $first['name']);
        $this->assertSame('123 Sesame Street New York NY 10001', $first['address']);

        $this->assertRequestHistory(['devices', 'pushes']);
    }
}
