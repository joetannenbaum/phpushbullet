<?php

namespace PHPushbullet\Request;

class PushList extends Request
{
    /**
	 * The type according to the Pushbullet API
	 *
	 * @var string $type
	 */

    protected $type = 'list';

    public function __construct($title, $items)
    {
        $this->parameters['title'] = $title;
        $this->parameters['items'] = $items;
    }
}
