<?php

namespace MySuccessStory\models;

/**
 * Class describing the object sent by the API
 * @author Jordan Folly
 */
class ApiValue
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
	public string $message;

    public int | string $errorCode;

	/**
	 * The received error code if there is one
	 *
	 * @var string
	 */
	public string $errorCode;

	/**
	 * @param mixed $value result of the request encoded in json
	 * @param string $message information or error message
	 * @param string $errorCode the error code thrown by the script if there is one
	 *
	 */
	public function __construct($value = null, $message = null, $errorCode = null)
	{
		$this->value = $value;
		$this->message = $message;
		$this->errorCode = $errorCode;
	}
}