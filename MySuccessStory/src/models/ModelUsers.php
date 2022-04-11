<?php

namespace MySuccessStory\models;

use MySuccessStory\db\DataBase;
use PDO;
use Exception;

class ModelUsers
{
	const TABLE_NAME = "users";

	/**
	 * Create a user
	 * @return ApiValue
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function createUser() : ApiValue
	{
		$data = ModelMain::getBody();

		try
		{
			if (!isset($data["email"])) return new ApiValue(null, "The email is missing", "400");
			if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) return new ApiValue(null, "The syntax of the email is incorrect", "400");

			$data["password"] = password_hash($data["password"], CRYPT_SHA256);

			$db = new DataBase();

			if ($db->insert(self::TABLE_NAME, $data))
			{
				$statement = $db->prepare('SELECT * FROM users WHERE email = "' . $data["email"] . '"');
				$statement->execute();

				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				unset($result[0]["password"]);

				return new ApiValue($result);
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
	 * Read a user
	 * @return ApiValue
	 *
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 * @author Beaud Rémy <remy.bd@eduge.ch>
	 *
	 */
	public static function readUser() : ApiValue
	{
		$token = ModelMain::getAuthorization();

		if ($token->value != null)
		{
			if (ModelMain::checkToken($token->value))
			{
				$idUser = ModelMain::getIdUser($token->value);

				try
				{
					$statement = (new DataBase())->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE idUser = '" . $idUser . "'");
					$statement->execute();
					$statementResult = $statement->fetchAll(PDO::FETCH_OBJ);
					unset($statementResult[0]->password);

					return new ApiValue($statementResult);
				}
				catch (Exception $e)
				{
					return new ApiValue(null, $e->getMessage(), $e->getCode());
				}
			}
			else
			{
				return new ApiValue(null, "invalid token", "401");
			}
		}
		else
		{
			return new ApiValue(null, "no authentication token", "401");
		}
	}

	/**
	 * Update a user
	 * @return ApiValue
	 *
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 * @author Beaud Rémy <remy.bd@eduge.ch>
	 */
	public static function updateUser() : ApiValue
	{
		$token = ModelMain::getAuthorization();

		if ($token->value != null)
		{
			if (ModelMain::checkToken($token->value))
			{
				$pdo = new DataBase();
				$idUser = ModelMain::getIdUser($token->value);

				$data = ModelMain::getBody();

				$statement = $pdo->prepare("SELECT idUser FROM users WHERE idUser = '$idUser'");
				$statement->execute();

				$idUser = $statement->fetchAll(PDO::FETCH_ASSOC)[0]["idUser"];

				if (isset($data["password"]))
				{
					$data["password"] = password_hash($data["password"], CRYPT_SHA256);
				}

				$statement = $pdo->prepare("SELECT * FROM " . self::TABLE_NAME . " LIMIT 1");
				$statement->execute();

				foreach (array_keys($statement->fetchAll(PDO::FETCH_ASSOC)[0]) as $key)
				{
					$exceptedFields = array("idUser", "email", "isTeacher");
					if (!isset($data[$key]) || in_array($key, $exceptedFields))
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
					$statementResult = $statement->fetchAll(PDO::FETCH_ASSOC);

					unset($statementResult[0]["password"]);
					return new ApiValue($statementResult, "The user has been edited");
				}
				catch (Exception $e)
				{
					return new ApiValue(null, $e->getMessage(), $e->getCode());
				}
			}
			else
			{
				return new ApiValue(null, "invalid token", "401");
			}
		}
		else
		{
			return new ApiValue(null, "no authentication token", "401");
		}
	}

	/**
	 * Delete a user
	 * @return ApiValue
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function deleteUser() : ApiValue
	{
		$token = ModelMain::getAuthorization();

		if ($token->value != null)
		{
			if (ModelMain::checkToken($token->value))
			{
				$idUser = ModelMain::getIdUser($token->value);

				try
				{
					(new DataBase)->delete(self::TABLE_NAME, "idUser = '$idUser'")->execute();
					return new ApiValue(null, "The user has been deleted");
				}
				catch (Exception $e)
				{
					return new ApiValue(null, $e->getMessage(), $e->getCode());
				}
			}
			else
			{
				return new ApiValue(null, "invalid token", "401");
			}
		}
		else
		{
			return new ApiValue(null, "no authentication token", "401");
		}
	}
}