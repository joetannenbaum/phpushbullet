<?php

namespace PHPushbullet\Request;

class PushFile extends Request
{
    /**
	 * The type according to the Pushbullet API
	 *
	 * @var string $type
	 */

    protected $type = 'file';

    public function __construct($file_name, $file_url, $body = null)
    {
        $this->parameters['file_name'] = $file_name;
        $this->parameters['file_url']  = $file_url;
        $this->parameters['file_type'] = $this->getFileType($file_url);
        $this->parameters['body']      = $body;
    }

    /**
	 * Get the file type based on the file url
	 *
	 * @param string $file_url
	 * @return string
	 */

    protected function getFileType($file_url)
    {
        $client    = new \GuzzleHttp\Client();
        $file_info = $client->head($file_url);

        return $file_info->getHeader('content-type');
    }
}
