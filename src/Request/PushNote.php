<?php

namespace PHPushbullet\Request;

class PushNote extends Request
{
    /**
	 * The type according to the Pushbullet API
	 *
	 * @var string $type
	 */

    protected $type = 'note';

    public function __construct($title, $body)
    {
        $this->parameters['title'] = $title;
        $this->parameters['body']  = $body;
    }
}
