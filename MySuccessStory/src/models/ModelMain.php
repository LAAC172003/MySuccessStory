<?php

namespace MySuccessStory\models;
use Exception;

class ModelMain
{
	const SALT = "1441caa2afec313f8fd620d9ed6492258b61fca73bb3f3ed6bc8691637bf96ef";
	const EXPIRATION_TIME = 3600;

	/**
	 * Returns the token
	 * @return ApiValue
	 *
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 * @author Beaud Rémy <remy.bd@eduge.ch>
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 */
	public static function getAuthorization() : ApiValue
	{
		$tokens = apache_request_headers();

		if (!isset($tokens['Authorization']))
		{
			return ModelMain::checkHttpCode(new ApiValue(null, "Authentification error : You need a bearer token to send a request", "401"));
		}

		return ModelMain::checkHttpCode(new ApiValue(explode(" ", $tokens['Authorization'])[1]));
	}

	/**
	 * Get the body
	 *
	 * @return object|array|null content of the body if there is one, null otherwise
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 */
	public static function getBody() : object|array|null
	{
		$data = json_decode(file_get_contents('php://input'), true);

		return $data;
	}

	/**
	 * decode the url in base64
	 * @param string|null $token
	 * @return ApiValue the decoded jwt if there is one
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 * @author Beaud Rémy <remy.bd@eduge.ch>
	 */
	public static function decryptJwt(?string $token) : ApiValue
	{
		if ($token == null)
		{
			return ModelMain::checkHttpCode(new ApiValue(null, "Incorrect token", "401"));
		}

		$tokenParts = explode(".", $token);

		return ModelMain::checkHttpCode
			(
				new ApiValue
				(
					array
					(
						"headers" => json_decode(base64_decode($tokenParts[0])),
						"payload" => json_decode(base64_decode($tokenParts[1])),
						"signature" => $tokenParts[2]
					)
				)
			);
	}

	/**
	 * Generates a token
	 * @return ApiValue returns the token
	 * @link https://developer.okta.com/blog/2019/02/04/create-and-verify-jwts-in-php
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 * @author Beaud Rémy <remy.bd@eduge.ch>
	 */
	public static function generateJwt(string $email, string $password): ApiValue
	{
		$headers = array("alg" => "HS256", "typ" => "JWT");
		$payload = array("email" => $email, "password" => $password, "expiration" => time() + self::EXPIRATION_TIME);
		$encodedHeaders = self::urlEncode(json_encode($headers));
		$encodedPayload = self::urlEncode(json_encode($payload));
		$signature = hash_hmac("MD5", "$encodedHeaders.$encodedPayload", self::SALT, true);
		$encodedSignature = self::urlEncode($signature);
		$token = "$encodedHeaders.$encodedPayload.$encodedSignature";
		return new ApiValue(
			[
				"token" => $token,
				"expiration" => self::EXPIRATION_TIME
			]
		);
	}

	/**
	 * encode the url in base64
	 * @param string $str
	 * @return string return the encoded code in base64
	 * @link https://developer.okta.com/blog/2019/02/04/create-and-verify-jwts-in-php
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function urlEncode(string $str): string
	{
		return rtrim(strtr(base64_encode($str), "+/", "-_"), "=");
	}

	/**
	 * Encode the ApiValue in JSON and send a http response code
	 * @param ApiValue $value Api value to encode
	 * @return string The result of the encoding
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 */
	public static function printJsonValue(ApiValue $value) : string
	{
		return json_encode(self::checkHttpCode($value));
	}

	/**
	 * Check what http code to use based on the passed value
	 * @param ApiValue $value Api value to check
	 * @return ApiValue The given value
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 */
	static function checkHttpCode(ApiValue $value) : ApiValue
	{
		if ($value->errorCode == "")
		{
			if ($value->message == "")
			{
				http_response_code(200);
			}
			else
			{
				http_response_code(204);
			}
		}
		else
		{
			if (str_starts_with($value->errorCode, "4") && strlen($value->errorCode) == 3)
			{
				http_response_code($value->errorCode);
			}
			else
			{
				http_response_code(500);
			}
		}

		return $value;
	}

	/**
	 * Check what http code to use based on the passed value
	 * @param string $errorCode Code to check
	 * @return bool True if the error code is not recognized
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 */
	public static function checkSqlError(string $errorCode) : bool
	{
		return !($errorCode == "42S22" || $errorCode == "01000");
	}

	/**
	 * Check what http code to use based on the passed value
	 * @param object|array $data body of the request
	 * @param string $key the key to check
	 * @return Exception|bool An error if the key doesn't exists and true if it exists
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 */
	public static function checkPrimaryKey(object|array $data, string $key) : Exception|bool
	{
		if (!isset($data[$key]))
		{
			throw new Exception('Syntax error : no "' . $key . '" field found in the JSON object', "400");
		}
		else
		{
			return true;
		}
	}
}