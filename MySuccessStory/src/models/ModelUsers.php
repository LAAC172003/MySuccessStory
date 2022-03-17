<?php

namespace MySuccessStory\models;

use MySuccessStory\db\DataBase;
use PDO;
use Exception;

class ModelUsers
{
	/**
	 * Name of the table for the mysql request
	 */
	const TABLE_NAME = "users";

	/**
	 * Return a token
	 * @param $token
	 * @return bool
	 *
	 * @author Beaud Rémy <remy.bd@eduge.ch>
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function isValidTokenAccount($token) : bool
	{
		$email = self::getEmailToken($token);
		$password = self::getPasswordToken($token);

		if ($email != null && $password != null)
		{
			return self::isValidAccount($email, $password);
		}

		return false;
	}

	/**
	 * Return a token
	 * @param $email
	 * @param $password
	 * @return bool
	 *
	 * @author Beaud Rémy <remy.bd@eduge.ch>
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function isValidAccount($email, $password) : bool
	{
		$statement = (new DataBase())->prepare("SELECT password from " . self::TABLE_NAME . " WHERE email = '$email'");
		$statement->execute();
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);

		return $result && $password == $result[0]["password"];
	}

	/**
	 * Return a token
	 * @author Beaud Rémy <remy.bd@eduge.ch>
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function getToken() : ApiValue
	{
		$data = ModelMain::getBody();

		if ($data && isset($data['email']) && isset($data['password']))
		{
			if (ModelUsers::isValidAccount($data['email'], $data['password']))
			{
				return new ApiValue(ModelMain::generateJwt($data['email'], $data['password']));
			}

			return new ApiValue(null, "error : invalid login", "0");
		}

		return new ApiValue(null, "Syntax error : the sent body doesn't contain email and password", "400");
	}

	/**
	 * Creates a user
	 * @return ApiValue
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 */
	public static function createUser() : ApiValue
	{
		$data = ModelMain::getBody();

		try
		{
			(new DataBase())->insert(self::TABLE_NAME, $data);
			return new ApiValue($data);
		}
		catch (Exception $e)
		{
			return new ApiValue(null, $e->getMessage(), $e->getCode());
		}
	}

	/**
	 * Returns the email
	 * @param $token
	 * @return string|null
	 * @author Beaud Rémy <remy.bd@eduge.ch>
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function getEmailToken($token) : ?string
	{
		$parts = ModelMain::decryptJwt($token->value)->value;
		return (isset($parts["payload"]->email)) ? $parts["payload"]->email : null;
	}

	/**
	 * Returns the password
	 * @param $token
	 * @return string|null
	 * @author Beaud Rémy <remy.bd@eduge.ch>
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function getPasswordToken($token) : ?string
	{
		$parts = ModelMain::decryptJwt($token->value)->value;
		return (isset($parts['payload']->password)) ? $parts['payload']->password : null;
	}

	/**
	 * Shows a user
	 * @return ApiValue
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 */
	public static function readUser() : ApiValue
	{
		$token = ModelMain::getAuthorization();

		if (self::isValidTokenAccount($token))
		{
			$email = self::getEmailToken($token);

			try
			{
				$statement = (new DataBase())->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE email = '" . $email . "'");
				$statement->execute();
				$statementResult = $statement->fetchAll(PDO::FETCH_OBJ);

				if ($statementResult)
				{
					return new ApiValue($statementResult);
				}
				else
				{
					return new ApiValue();
				}
			}
			catch (Exception $e)
			{
				return new ApiValue(null, $e->getMessage(), $e->getCode());
			}
		}
		else
		{
			return new ApiValue(null, "invalid token", "403");
		}
	}

	/**
	 * updates a user
	 * @return ApiValue
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 */
	public static function updateUser() : ApiValue
	{
		$token = ModelMain::getAuthorization();

		if (self::isValidTokenAccount($token))
		{
			$email = self::getEmailToken($token);
			$data = ModelMain::getBody();

			if (!$data)
			{
				return new ApiValue(null, "Syntax error : the sent body is not a valid JSON object", "0");
			}

			try
			{
				ModelMain::checkPrimaryKey($data, "idUser");

				(new DataBase())->update(self::TABLE_NAME, $data, "idUser = " . $data['idUser']);
				return new ApiValue($data);
			}
			catch (Exception $e)
			{
				return new ApiValue(null, $e->getMessage(), $e->getCode());
			}
		}
		else
		{
			return new ApiValue(null, "invalid token", "403");
		}
	}

	/**
	 * delete an user
	 * @return ApiValue
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 */
	public static function deleteUser() : ApiValue
	{
		$token = ModelMain::getAuthorization();

		if (self::isValidTokenAccount($token))
		{
			$email = self::getEmailToken($token);
			$data = ModelMain::getBody();

			try
			{
				ModelMain::checkPrimaryKey($data, "idUser");

				(new DataBase())->delete(self::TABLE_NAME, "idUser = " . $data['idUser'])->execute();
				return new ApiValue($data);
			}
			catch (Exception $e)
			{
				return new ApiValue(null, $e->getMessage(), $e->getCode());
			}
		}
		else
		{
			return new ApiValue(null, "invalid token", "403");
		}
	}
}