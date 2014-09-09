<?php

namespace PHPushbullet\Request;

class PushLink extends Request {

	/**
	 * The type according to the Pushbullet API
	 *
	 * @var string $type
	 */

	protected $type = 'link';

	public function __construct( $title, $url, $body = NULL )
	{
		$this->parameters['title'] = $title;
		$this->parameters['url']   = $url;
		$this->parameters['body']  = $body;
	}
}