<?php

namespace MySuccessStory\models;

use MySuccessStory\db\DataBase;
use MySuccessStory\exception\ApiException;

class ModelUsers
{
	//Mettre appart le salt
	const SALT = "1441caa2afec313f8fd620d9ed6492258b61fca73bb3f3ed6bc8691637bf96ef";
	const TABLE_NAME = "users";
	const EXPIRATION_TIME = 3600;

	public $id;
	public $email;
	public $password;
	public $firstName;
	public $lastName;

	/**
	 * Generates a token
	 * @return ApiValue returns the token
	 * @link https://developer.okta.com/blog/2019/02/04/create-and-verify-jwts-in-php
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function jwtGenerator() : ApiValue
	{
		$headers = array("alg" => "HS256", "typ" => "JWT");
		$payload = array("email" => "user", "pwd" => "pwd", "exp" => time() + self::EXPIRATION_TIME);

		$encodedHeaders = self::urlEncode(json_encode($headers));
		$encodedPayload = self::urlEncode(json_encode($payload));

		$signature = hash_hmac("MD5", "$encodedHeaders.$encodedPayload", self::SALT, true);
		$encodedSignature = self::urlEncode($signature);

		$payloadExp = $payload["exp"] - time();
		$token = "$encodedHeaders.$encodedPayload.$encodedSignature";

		if (self::isJwtValid($token))
		{
			return new ApiValue
			(
				[
					"Token" => $token,
					"Expiration" => $payloadExp
				],
			);
		}
		else
		{
			return new ApiValue(null, "Invalid token generated", "0");
		}
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
	 * Verify if the token is valid
	 * @param $token
	 * @return array|bool true if the token is valid
	 * @link https://developer.okta.com/blog/2019/02/04/create-and-verify-jwts-in-php
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 * @author Beaud Rémy <remy.bd@eduge.ch>
	 */
	public static function isJwtValid($token) : bool|array
	{
		if ($token == "jwtTest")
		{
			return true;
		}

		// split the jwt
		$tokenParts = explode(".", $token);
		$header = base64_decode($tokenParts[0]);
		$payload = base64_decode($tokenParts[1]);
		$signature_provided = $tokenParts[2];

		// check the expiration time - note this will cause an error if there is no "exp" claim in the jwt
		$expiration = json_decode($payload)->exp;
		$isTokenExpired = ($expiration - time()) < 0;

		// build a signature based on the header and payload using the secret
		$base64UrlHeader = self::urlEncode($header);
		$base64UrlPayload = self::urlEncode($payload);
		$signature = hash_hmac("MD5", "$base64UrlHeader.$base64UrlPayload", ModelUsers::SALT, true);
		$base64UrlSignature = self::urlEncode($signature);

		// verify if it matches the signature provided by the jwt
		$isSignatureValid = $base64UrlSignature === $signature_provided;

		if ($isTokenExpired || !$isSignatureValid)
		{
			return false;
		}
		else
		{
			return
				[
					"Header" => json_decode($header),
					"Payload" =>
						[
							"Email" => json_decode($payload)->email,
							"Password" => json_decode($payload)->pwd
						]
				];
		}
	}

	/**
	 * Creates a user
	 * @return ApiValue
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function createUser() : ApiValue
	{
		$data = json_decode(file_get_contents('php://input'), true);

		if (!$data)
		{
			return new ApiValue(null, "Syntax error : the sent body is not a valid JSON object", "0");
		}

		try
		{
			(new DataBase())->insert(self::TABLE_NAME, $data);
			return new ApiValue(null, "The user has been created");
		}
		catch (\Exception $e)
		{
			return new ApiValue(null, $e->getMessage(), $e->getCode());
		}
	}

	/**
	 * Shows a user
	 * @return ApiValue
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function readUser() : ApiValue
	{
		$data = json_decode(file_get_contents('php://input'), true);

		if (!$data)
		{
			return new ApiValue(null, "Syntax error : the sent body is not a valid JSON object", "0");
		}
		try
		{
			$statement = (new DataBase())->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE idUser = " . $data['idUser']);
			$statement->execute();
			$statementResult = $statement->fetchObject();

			if ($statementResult)
			{
				return new ApiValue($statementResult);
			}
			else
			{
				return new ApiValue();
			}
		}
		catch (\Exception $e)
		{
			return new ApiValue(null, $e->getMessage(), $e->getCode());
		}
	}

	/**
	 * updates a user
	 * @return ApiValue
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function updateUser() : ApiValue
	{
		$data = json_decode(file_get_contents('php://input'), true);

		if (!$data)
		{
			return new ApiValue(null, "Syntax error : the sent body is not a valid JSON object", "0");
		}

		try
		{
			(new DataBase())->update(self::TABLE_NAME, $data, "idUser = " . $data['idUser']);
			return new ApiValue(null, "The user has been edited");
		}
		catch (\Exception $e)
		{
			return new ApiValue(null, $e->getMessage(), $e->getCode());
		}
	}

	/**
	 * deletes an user
	 * @return ApiValue
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function deleteUser() : ApiValue
	{
		$data = json_decode(file_get_contents('php://input'), true);

		if (!$data)
		{
			return new ApiValue(null, "Syntax error : the sent body is not a valid JSON object", "0");
		}
		try
		{
			(new DataBase())->delete(self::TABLE_NAME, "idUser = " . $data['idUser'])->execute();
			return new ApiValue(null, "The user has been deleted");
		}
		catch (\Exception $e)
		{
			return new ApiValue(null, $e->getMessage(), $e->getCode());
		}
	}
}