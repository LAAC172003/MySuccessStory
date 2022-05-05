<?php

namespace MySuccessStory\models;
use MySuccessStory\db\DataBase;
use PDO;
use Exception;
use JetBrains\PhpStorm\Internal\ReturnTypeContract;

class ModelSubjects
{
	/**
	 * Name of the table for the mysql request
	 */
	const TABLE_NAME = "subjects";

	/**
	 * Create a subject
	 *
	 * @return ApiValue
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 */
	public static function createSubject() : ApiValue
	{
		$token = ModelMain::getAuthorization();

		if ($token->value != null)
		{
			if (ModelMain::checkToken($token->value))
			{
				$data = ModelMain::getBody();
				$idUser = ModelMain::getIdUser($token->value);

				$pdo = new DataBase();

				if (!ModelMain::checkIfTeacher($idUser)) return new ApiValue(null, "You have to be a teacher to add a subject", "403");

				if (isset($data["name"]))
				{
					$name = $data["name"];
				}
				else
				{
					return new ApiValue(null, "the name is missing", "400");
				}

				if (isset($data["description"]))
				{
					$description = $data["description"];
				}
				else
				{
					return new ApiValue(null, "the description is missing", "400");
				}

				if (isset($data["isCIE"]))
				{
					$isCIE = $data["isCIE"];
				}
				else
				{
					return new ApiValue(null, "isCIE is missing", "400");
				}

				if (isset($data["category"]))
				{
					$category = $data["category"];
				}
				else
				{
					return new ApiValue(null, "the category is missing", "400");
				}

				$statement = $pdo->prepare("SELECT * FROM subjects WHERE `name` = '$name'");
				$statement->execute();
				if (isset($statement->fetchAll(PDO::FETCH_ASSOC)[0]))
				{
					return new ApiValue(null, "The subject already exists", "400");
				}

				if (($isCIE !== true && $isCIE !== false) && ($isCIE != 1 && $isCIE != 0))
				{
					return new ApiValue(null, "isCIE has to be true or false", "400");
				}

				$statement = $pdo->prepare("SHOW COLUMNS FROM " . self::TABLE_NAME . " LIKE 'category'");
				$statement->execute();

				if (!in_array("'$category'", explode(",", substr(trim($statement->fetchAll(PDO::FETCH_ASSOC)[0]["Type"], ")"), 5))))
				{
					return new ApiValue(null, "The category doesn't exist", "400");
				}

				try
				{
					$statement = $pdo->prepare("INSERT INTO " . self::TABLE_NAME . "(`name`, `description`, `isCIE`, `category`) VALUES ('$name', '$description', " . json_encode($isCIE) . ", '$category')");
					$statement->execute();

					$statement = $pdo->prepare("SELECT * FROM subjects WHERE `name` = '$name'");
					$statement->execute();

					return new ApiValue($statement->fetchAll(PDO::FETCH_ASSOC)[0], "The subject has been added");
				}
				catch (Exception $e)
				{
					return new ApiValue(null, $e->getMessage(), $e->getCode());
				}
			}
			return new ApiValue(null, "invalid token", "401");
		}
		else
		{
			return new ApiValue(null, "no authentication token", "401");
		}
	}

	/**
	 * Read all the subjects
	 *
	 * @return ApiValue
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 */
	public static function readSubjects() : ApiValue
	{
		$token = ModelMain::getAuthorization();

		if ($token->value != null)
		{
			if (ModelMain::checkToken($token->value))
			{
				$data = ModelMain::getBody();

				$query = "SELECT * FROM " . self::TABLE_NAME . " WHERE true";

				if (isset($data["id"]))
				{
					$idSubject = $data["id"];

					if (!is_int($idSubject)) return new ApiValue(null, "The id has to be an integer number", "400");

					$query .= " AND idSubject = $idSubject";
				}

				if (isset($data["name"]))
				{
					$name = $data["name"];

					$query .= " AND name = '$name'";
				}

				if (isset($data["description"]))
				{
					$description = $data["description"];

					$query .= " AND description LIKE '%$description%'";
				}

				if (isset($data["isCIE"]))
				{
					$isCIE = $data["isCIE"];

					if ($isCIE !== true && $isCIE !== false && $isCIE !== 0 && $isCIE !== 1)
						return new ApiValue(null, "isCIE has to be true or false");

					$query .= " AND isCIE = " . json_encode($isCIE);
				}

				if (isset($data["category"]))
				{
					$category = $data["category"];

					$statement = (new DataBase())->prepare("SHOW COLUMNS FROM " . self::TABLE_NAME . " LIKE 'category'");
					$statement->execute();

					if (!in_array("'$category'", explode(",", substr(trim($statement->fetchAll(PDO::FETCH_ASSOC)[0]["Type"], ")"), 5))))
					{
						return new ApiValue(null, "The category doesn't exist", "400");
					}

					$query .= " AND category = '$category'";
				}

				try
				{
					$statement = (new DataBase())->prepare($query);
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
				return new ApiValue(null, "invalid token", "401");
			}
		}
		else
		{
			return new ApiValue(null, "no authentication token", "401");
		}
	}

	/**
	 * Update a subject
	 *
	 * @return ApiValue
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 */
	public static function updateSubject() : ApiValue
	{
		$token = ModelMain::getAuthorization();

		if ($token->value != null)
		{
			if (ModelMain::checkToken($token->value))
			{
				$data = ModelMain::getBody();
				$idUser = ModelMain::getIdUser($token->value);

				if (!ModelMain::checkIfTeacher($idUser)) return new ApiValue(null, "You have to be a teacher to edit a subject", "403");

				if (isset($data["idSubject"]))
				{
					$idSubject = $data["idSubject"];
					unset($data["idSubject"]);
				}
				else
				{
					return new ApiValue(null, "idSubject has not been filled in", "400");
				}

				if (isset($data["isCIE"]))
				{
					if ($data["isCIE"] !== true && $data["isCIE"] !== false && $data["isCIE"] !== 0 && $data["isCIE"] !== 1) return new ApiValue(null, "isCIE has to be true or false");

					$data["isCIE"] = (int)$data["isCIE"];
				}

				$pdo = new DataBase();

				if (isset($data["category"]))
				{
					$statement = $pdo->prepare("SHOW COLUMNS FROM " . self::TABLE_NAME . " LIKE 'category'");
					$statement->execute();

					if (!in_array("'" . $data["category"] . "'", explode(",", substr(trim($statement->fetchAll(PDO::FETCH_ASSOC)[0]["Type"], ")"), 5))))
					{
						return new ApiValue(null, "The category doesn't exist", "400");
					}
				}

				$statement = $pdo->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE idSubject = $idSubject");
				$statement->execute();
				$subject = $statement->fetchAll(PDO::FETCH_ASSOC);

				if (isset($subject[0]))
				{
					$subject = $subject[0];
				}
				else
				{
					return new ApiValue(null, "This subject doesn't exist", "400");
				}

				try
				{
					$pdo->update(self::TABLE_NAME, $data, "idSubject = $idSubject");
					$statement->execute();

					return new ApiValue($statement->fetchAll(PDO::FETCH_ASSOC), "The subject has been edited");
				}
				catch (Exception $e)
				{
					return new ApiValue(null, $e->getMessage(), $e->getCode());
				}
			}
		}
		else
		{
			return new ApiValue(null, "no authentication token", "401");
		}

		return new ApiValue(null, "invalid token", "401");
	}

	/**
	 * Delete a subject
	 *
	 * @return ApiValue
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 */
	public static function deleteSubject() : ApiValue
	{
		$token = ModelMain::getAuthorization();

		if ($token->value != null)
		{
			if (ModelMain::checkToken($token->value))
			{
				$data = ModelMain::getBody();

				if (!isset($data["name"])) return new ApiValue(null, "The name has not been filled in", "400");

				$name = $data["name"];

				$pdo = new DataBase();

				$statement = $pdo->prepare("SELECT idSubject FROM subjects WHERE name = '$name'");
				$statement->execute();
				$idSubject = $statement->fetchAll(PDO::FETCH_ASSOC);

				if (isset($idSubject[0]))
				{
					$idSubject = $idSubject[0]["idSubject"];
				}
				else
				{
					return new ApiValue(null, "The subject doesn't exist", "400");
				}

				try
				{
					$pdo->delete(self::TABLE_NAME, "idSubject = '$idSubject'")->execute();
					return new ApiValue(null, "The subject has been deleted");
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