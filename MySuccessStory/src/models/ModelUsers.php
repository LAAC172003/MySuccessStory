<?php

namespace MySuccessStory\models;

use MySuccessStory\db\DataBase;
use PDO;
use Exception;
use MySuccessStory\controllers\ControllerUsers;

class ModelUsers
{
	const TABLE_NAME = "users";

	/**
	 * Return a token
	 * @param $token
	 * @return bool
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
	 * @author Beaud Rémy <remy.bd@eduge.ch>
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function isValidAccount($email, $password) : bool
	{
		$statement = (new DataBase())->prepare("SELECT password FROM " . self::TABLE_NAME . " WHERE email = '$email'");
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
			if (ModelUsers::isValidAccount($data['email'], $data['password']))
			{
				return ModelMain::generateJwt($data['email'], $data['password']);
			}

			return new ApiValue(null, "invalid user or password", "400");
		}

		return new ApiValue(null, "the sent body doesn't contain email and password", "400");
	}


	/**
	 * Creates a user
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function createUser()
	{
		$data = ModelMain::getBody();

		try
		{
			$data["password"] = password_hash($data["password"], CRYPT_SHA256);

			$db = new DataBase();
			if ($db->insert(self::TABLE_NAME, $data))
			{
				$statement = $db->prepare('SELECT * FROM users WHERE email = "' . $data["email"] . '"');
				$statement->execute();

				return new ApiValue($statement->fetchAll(PDO::FETCH_ASSOC));
			}
			else
			{
				return new ApiValue(null, "The user has not been created (probably because it already exists)", "0");
			}
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
		$parts = ModelMain::decryptJwt($token->value);
		if (isset($parts->value['payload']->email)) return $parts->value['payload']->email;
		return "invalid token";
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
		$parts = ModelMain::decryptJwt($token->value);
		if (isset($parts->value['payload']->password)) return $parts->value['payload']->password;
		return null;
	}

	// /**
	//  * Shows a user
	//  * @return ApiValue
	//  * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	//  * @author Beaud Rémy <remy.bd@eduge.ch>
	//  *
	//  */
	// public static function readUser() : ApiValue
	// {
	// 	$token = ModelMain::getAuthorization();

	// 	if (self::isValidTokenAccount($token))
	// 	{
	// 		$email = self::getEmailToken($token);
	// 		try
	// 		{
	// 			$statement = (new DataBase())->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE email = '" . $email . "'");
	// 			$statement->execute();
	// 			$statementResult = $statement->fetchAll(PDO::FETCH_OBJ);

	// 			if ($statementResult)
	// 			{
	// 				return new ApiValue($statementResult);
	// 			}
	// 			else
	// 			{
	// 				return new ApiValue();
	// 			}
	// 		}
	// 		catch (Exception $e)
	// 		{
	// 			return new ApiValue(null, $e->getMessage(), $e->getCode());
	// 		}
	// 	}
	// 	else
	// 	{
	// 		return new ApiValue(null, "invalid token");
	// 	}
	// }

	/**
	 * Update a user
	 * @return ApiValue
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 * @author Beaud Rémy <remy.bd@eduge.ch>
	 */
	public static function updateUser() : ApiValue
	{
		$token = ModelMain::getAuthorization();

		if (self::isValidTokenAccount($token))
		{
			$pdo = new DataBase();
			$email = self::getEmailToken($token);

			$data = ModelMain::getBody();

			$statement = $pdo->prepare('SELECT idUser FROM users WHERE email = "' . $email . '"');
			$statement->execute();

			$idUser = $statement->fetchAll(PDO::FETCH_ASSOC)[0]["idUser"];

			if ($idUser != $data["idUser"])
			{
				return new ApiValue(null, "You cannot edit someone else's account. You may have wrote the wrong idUser", "400");
			}

			if (isset($data["password"]))
			{
				$data["password"] = password_hash($data["password"], CRYPT_SHA256);
			}

			$statement = $pdo->prepare("SELECT * FROM " . self::TABLE_NAME . " LIMIT 1");
			$statement->execute();

			foreach (array_keys($statement->fetchAll(PDO::FETCH_ASSOC)[0]) as $key)
			{
				if (!isset($data[$key]) || $key == "idUser" || $key == "email")
				{
					$statement = $pdo->prepare("SELECT $key FROM " . self::TABLE_NAME . " WHERE idUser = $idUser");
					$statement->execute();
					$data[$key] = $statement->fetchAll(PDO::FETCH_ASSOC)[0][$key];
				}
			}

			try
			{
				$pdo->update(self::TABLE_NAME, $data, "idUser = $idUser");

				$statement = $pdo->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE idUser = $idUser");
				$statement->execute();

				return new ApiValue($statement->fetchAll(PDO::FETCH_ASSOC), "The user has been edited");
			}
			catch (Exception $e)
			{
				return new ApiValue(null, $e->getMessage(), $e->getCode());
			}
		}
		else
		{
			return new ApiValue(null, "invalid token");
		}
	}

	/**
	 * deletes an user
	 * @return ApiValue
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function deleteUser() : ApiValue
	{
		$token = ModelMain::getAuthorization();

		if (self::isValidTokenAccount($token))
		{
			$email = self::getEmailToken($token);

			try
			{
				(new DataBase)->delete(self::TABLE_NAME, "email= '$email'")->execute();
				return new ApiValue(null, "The user has been deleted");
			}
			catch (Exception $e)
			{
				return new ApiValue(null, $e->getMessage(), $e->getCode());
			}
		}
		else
		{
			return new ApiValue(null, "invalid token");
		}
	}
}