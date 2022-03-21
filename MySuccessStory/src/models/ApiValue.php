<?php

namespace MySuccessStory\models;

/**
 * Class describing the object sent by the API
 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
 */
class ApiValue
{
	/**
	 * Result of the request encoded in json
	 *
	 * @var mixed
	 */
	public mixed $value;

	/**
	 * Information or error message
	 *
	 * @var string
	 */
	public string $message;

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
	public function __construct(mixed $value = null, string $message = "", string $errorCode = "")
	{
		$this->value = $value;
		$this->message = $message;
		$this->errorCode = $errorCode;
	}
}