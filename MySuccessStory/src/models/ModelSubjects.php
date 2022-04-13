<?php

namespace MySuccessStory\models;
use MySuccessStory\db\DataBase;
use PDO;
use Exception;

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

				if (isset($data["year"]))
				{
					$year = $data["year"];
				}
				else
				{
					return new ApiValue(null, "the year is missing", "400");
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

				if (!is_int($year)) return new ApiValue(null, "The year has to be an integer number", "400");
				if ($year < 0 || $year > 4) return new ApiValue(null, "The year has to be between 0 and 4", "400");

				try
				{
					$statement = $pdo->prepare("INSERT INTO " . self::TABLE_NAME . "(`name`, `description`, `isCIE`, `category`, `year`) VALUES ('$name', '$description', $isCIE, '$category', $year)");
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
				try
				{
					$statement = (new DataBase())->prepare("SELECT * FROM " . self::TABLE_NAME);
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
}