<?php

namespace MySuccessStory\models;

/**
 * Class describing the object sent by the API
 * @author Jordan Folly
 */
class ModelApiValue
{
	/**
	 * Result of the request encoded in json
	 *
	 * @var string
	 */
	public $value;

	/**
	 * Information or error message
	 *
	 * @var string
	 */
	public $message;

	/**
	 * @param string $value result of the request encoded in json
	 * @param string $message information or error message
	 *
	 */
	public function __construct($value = null, $message = null)
	{
		$this->value = $value;
		$this->message = $message;
	}
}