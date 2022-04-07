<?php

namespace MySuccessStory\models;

use MySuccessStory\db\DataBase;
use Exception;
use PDO;

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
			return new ApiValue(null, "Authentification error : You need a bearer token to send a request", "401");
		}

		return new ApiValue(explode(" ", $tokens['Authorization'])[1]);
	}

	/**
	 * Get the body
	 *
	 * @return object|array|null content of the body if there is one, null otherwise
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 */
	public static function getBody() : object|array|null
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	/**
	 * Generates a token
	 * @return ApiValue returns the token
	 * @link https://developer.okta.com/blog/2019/02/04/create-and-verify-jwts-in-php
	 * @author Jordan Folly-Sodoga <ekoue-jordan.fllsd@eduge.ch>
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 * @author Beaud Rémy <remy.bd@eduge.ch>
	 */
	public static function generateJwt(string $email, string $firstName, string $lastName) : ApiValue
	{
		$expiration = time() + self::EXPIRATION_TIME;
		$headers = array("alg" => "HS256", "typ" => "JWT");
		$payload = array("email" => $email, "name" => "$firstName $lastName", "expiration" => $expiration);

		$encodedHeaders = self::urlEncode(json_encode($headers));
		$encodedPayload = self::urlEncode(json_encode($payload));

		$token = hash_hmac("MD5", hash_hmac("SHA256", "$encodedHeaders.$encodedPayload", self::SALT, true), self::SALT, true);
		$encodedToken = self::urlEncode($token);

		$pdo = new DataBase();

		$selectUserId = $pdo->prepare(("SELECT idUser FROM users WHERE email = '$email'"));
		$selectUserId->execute();
		$idUser = $selectUserId->fetchAll(PDO::FETCH_ASSOC)[0]["idUser"];

		$selectToken = $pdo->prepare(("SELECT idUser FROM token WHERE idUser = $idUser"));
		$selectToken->execute();

		$idUserCheck = $selectToken->fetchAll(PDO::FETCH_ASSOC);
		if (isset($idUserCheck[0]))
		{
			if (!is_null($idUserCheck[0]))
			{
				$updateToken = $pdo->prepare("UPDATE token SET token = '$encodedToken', expiration = $expiration WHERE idUser = $idUser");
				$updateToken->execute();
			}
			else
			{
				$insertToken = $pdo->prepare("INSERT INTO token (idUser, token, expiration) VALUES ($idUser, '$encodedToken', $expiration)");
				$insertToken->execute();
			}
		}
		else
		{
			$insertToken = $pdo->prepare("INSERT INTO token (idUser, token, expiration) VALUES ($idUser, '$encodedToken', $expiration)");
			$insertToken->execute();
		}

		return new ApiValue
			(
				[
					"token" => $encodedToken,
					"expiration" => self::EXPIRATION_TIME
				]
			);
	}

	// /**
	//  * decode the url in base64
	//  * @param string|null $token
	//  * @return ApiValue the decoded jwt if there is one
	//  * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	//  * @author Beaud Rémy <remy.bd@eduge.ch>
	//  */
	// public static function decryptJwt(?string $token) : ApiValue
	// {
	// 	if (!isset($token))
	// 	{
	// 		return new ApiValue(null, "no token provided", "401");
	// 	}

	// 	$tokenParts = explode(".", $token);

	// 	if (isset($tokenParts[0]) && isset($tokenParts[1]) && isset($tokenParts[2]))
	// 	{
	// 		return new ApiValue
	// 			(
	// 				array
	// 				(
	// 					"headers" => json_decode(base64_decode($tokenParts[0])),
	// 					"payload" => json_decode(base64_decode($tokenParts[1])),
	// 					"signature" => $tokenParts[2]
	// 				)
	// 			);
	// 	}

	// 	return new ApiValue(null, "invalid token", "401");
	// }

	/**
	 * encode the url in base64
	 * @param string $str
	 * @return string return the encoded code in base64
	 * @link https://developer.okta.com/blog/2019/02/04/create-and-verify-jwts-in-php
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function urlEncode(string $str) : string
	{
		return rtrim(strtr(base64_encode($str), "+/", "-_"), "=");
	}

	/**
	 * Return a token
	 * @param string $token
	 * @return bool
	 * @author Beaud Rémy <remy.bd@eduge.ch>
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function checkToken($token) : bool
	{
		$pdo = new DataBase();

		$statement = $pdo->prepare("SELECT token, expiration FROM token WHERE token = '$token'");
		$statement->execute();
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);

		if (isset($result[0]["token"]))
		{
			if ($result[0]["expiration"] < time())
			{
				$statement = $pdo->prepare("DELETE FROM token WHERE token = '" . $result[0]["token"] . "'");
				$statement->execute();
				return false;
			}

			return $token == $result[0]["token"];
		}

		return false;
	}

	/**
	 * Return a token
	 * @param string $email
	 * @param string $password
	 * @return bool
	 * @author Beaud Rémy <remy.bd@eduge.ch>
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function authenticate(string $email, string $password) : bool
	{
		$statement = (new DataBase())->prepare("SELECT password FROM users WHERE email = '$email'");
		$statement->execute();
		$result = $statement->fetchAll(PDO::FETCH_OBJ);
		return (isset($result[0]->password) && password_verify($password, $result[0]->password));
	}

	/**
	 * Return a token
	 * @return ApiValue
	 *
	 * @author Beaud Rémy <remy.bd@eduge.ch>
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function getToken() : ApiValue
	{
		$data = ModelMain::getBody();

		if (isset($data['email']) && isset($data['password']))
		{
			if (self::authenticate($data["email"], $data["password"]))
			{
				$statement = (new DataBase())->prepare("SELECT firstName, lastName FROM users WHERE email = '" . $data["email"] . "'");
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_OBJ);

				return ModelMain::generateJwt($data['email'], $result[0]->firstName, $result[0]->lastName);
			}

			return new ApiValue(null, "invalid user or password", "400");
		}

		return new ApiValue(null, "the sent body doesn't contain email and password", "400");
	}

	/**
	 * Find the id of the user in the database
	 * @param string $token the autentication token
	 * @return int the found user id or -1 if it has not been found
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 */
	public static function getIdUser($token) : int
	{
		if (self::checkToken($token))
		{
			$statement = (new DataBase())->prepare("SELECT * FROM token WHERE token = '$token'");
			$statement->execute();
			return $statement->fetchAll(PDO::FETCH_OBJ)[0]->idUser;
		}
		else
		{
			return -1;
		}
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
		header("Content-Type:application/json");

		if ($value->errorCode == "")
		{
			http_response_code(200);
		}
		else
		{
			if (str_starts_with($value->errorCode, "4") && strlen($value->errorCode) == 3)
			{
				if ($value->errorCode == "401")
				{
					header('WWW-Authenticate: Bearer Token');
				}

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
