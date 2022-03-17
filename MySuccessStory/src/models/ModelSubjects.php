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
	 * Read all the subjects
	 *
	 * @return ApiValue
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 */
	public static function readSubjects() : ApiValue
	{
		$token = ModelMain::getAuthorization();

		if (ModelUsers::isValidTokenAccount($token))
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
			return new ApiValue(null, "invalid token", "403");
		}
	}
}