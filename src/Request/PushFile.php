<?php

namespace PHPushbullet\Request;

use GuzzleHttp\Client;

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
     *
     * @return string
     */

    protected function getFileType($file_url)
    {
        $file_info = (new Client())->head($file_url);
        $file_type = $file_info->getHeader('content-type');

        if (is_array($file_type)) {
            return reset($file_type);
        }

        return $file_type;
    }
}
