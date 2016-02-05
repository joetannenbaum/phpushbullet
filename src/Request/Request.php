<?php

namespace PHPushbullet\Request;

abstract class Request
{
    /**
     * The type according to the Pushbullet API
     *
     * @var string $type
     */

    protected $type;

    /**
     * The parameters to pass to the API
     *
     * @var array $parameters
     */

    protected $parameters = [];

    public function request()
    {
        return array_merge(['type' => $this->type], $this->parameters);
    }
}
