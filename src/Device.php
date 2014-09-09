<?php

namespace PHPushbullet;

class Device {

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

	public function __construct( Array $attr )
	{
		foreach ( $this->fields as $field )
		{
			$method = 'set' . ucwords( $field );

			if ( method_exists( $this, $method ) )
			{
				// If there is a setter for this field, use that
				$this->$field = $this->$method( $attr[ $field ] );
			}
			else
			{
				// Otherwise just set the property
				$this->$field = $attr[ $field ];
			}
		}
	}

	/**
	 * Format the date so that it's readable
	 *
	 * @param integer $date
	 * @return string
	 */

	protected function setCreated( $date )
	{
		return date( 'Y-m-d h:i:sA T', $date );
	}

}