<?php

namespace PHPushbullet;

class Device
{
    /**
     * The fields that we want to retrieve for the device
     *
     * @var array $fields
     */

    protected $fields = [
        'nickname',
        'iden',
        'model',
        'type',
        'active',
        'pushable',
        'manufacturer',
        'created',
    ];

    public function __construct(array $attr)
    {
        foreach ($this->fields as $field) {
            $this->{$field} = $this->getFieldValue($attr, $field);
        }
    }

    /**
     * @param array $attr
     * @param string $field
     *
     * @return string
     */
    protected function getFieldValue(array $attr, $field)
    {
        $method = 'set' . ucwords($field);

        if (method_exists($this, $method)) {
            // If there is a setter for this field, use that
            return $this->{$method}($attr[$field]);
        }

        // Otherwise just set the property
        return (isset($attr[$field])) ? $attr[$field] : null;
    }

    /**
     * Format the date so that it's readable
     *
     * @param integer $date
     * @return string
     */

    protected function setCreated($date)
    {
        return date('Y-m-d h:i:sA T', $date);
    }
}
