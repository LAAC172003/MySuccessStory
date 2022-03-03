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
	public string $message;

    public int | string $errorCode;

	/**
	 * @param string|null $value result of the request encoded in json
	 * @param string|null $message information or error message
	 *
	 */
	public function __construct(mixed $value = null, string $message = null, $errorCode = null)
	{
		$this->value = $value;
		$this->message = $message;
		$this->errorCode = $errorCode;
	}
}