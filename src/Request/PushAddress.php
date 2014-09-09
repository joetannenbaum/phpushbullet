<?php

namespace PHPushbullet\Request;

class PushAddress extends Request
{
    /**
	 * The type according to the Pushbullet API
	 *
	 * @var string $type
	 */

    protected $type = 'address';

    public function __construct($name, $address)
    {
        $this->parameters['name']    = $name;
        $this->parameters['address'] = $this->setAddress($address);
    }

    /**
	 * The address can either be a string or an array,
	 * make sure it's a string in the end
	 *
	 * @param string|array $address
	 * @return string
	 */

    private function setAddress($address)
    {
        if (is_array($address)) {
            $new_address = [];

            foreach (['address', 'city', 'state', 'zip'] as $field) {
                if (array_key_exists($field, $address)) {
                    $new_address[] = $address[$field];
                }
            }

            $address = implode(' ', $new_address);
        }

        return $address;
    }
}
