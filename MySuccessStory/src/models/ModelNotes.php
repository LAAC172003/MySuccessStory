<?php

namespace MySuccessStory\models;

use MySuccessStory\db\DataBase;
use PDO;
use Exception;

class ModelNotes
{
	const TABLE_NAME = "notes";

	/**
	 * Create a note
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function createNote()
	{
		$token = ModelMain::getAuthorization();

		if (ModelUsers::isValidTokenAccount($token))
		{
			$data = ModelMain::getBody();
			$idUser = self::getIdUser($token);
			$data['idUser'] = $idUser;

			$subject = $data['idSubject'];
			$idSubject = (new DataBase())->select("SELECT idSubject FROM subjects WHERE name = '$subject'");
			$data['idSubject'] = $idSubject[0]->idSubject;

			if (!$idSubject) return "The subject is incorrect";
			if ($data['semester'] > 2) return "The semester can't be over 2";
			if ($data['note'] > 6) return "The note can't be over 6";

			try
			{
				(new DataBase())->insert(self::TABLE_NAME, $data);

				return new ApiValue($data, "The note has been added");
			}
			catch (Exception $e)
			{
				return new ApiValue(null, $e->getMessage(), $e->getCode());
			}
		}
		return new ApiValue(null, "invalid token", "0");
	}
    public static function getIdUser($token)
    {
        $email = ModelUsers::getEmailToken($token);
        $statementIdUser = (new DataBase())->select("SELECT idUser from users where email = '$email'");
        return $statementIdUser[0]->idUser;
    }

	/**
	 * Read a note
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function readNote() : ApiValue
	{
		$token = ModelMain::getAuthorization();

		if (ModelUsers::isValidTokenAccount($token))
		{
			$data = ModelMain::getBody();
			$idUser = self::getIdUser($token);

			try
			{
				$orderBy = "ASC";

				if (isset($data["Order"]))
				{
					if (strtoupper($data["Order"]) == "ASC" || strtoupper($data["Order"]) == "DESC")
					{
						$orderBy = strtoupper($data["Order"]);
					}
				}

				$statementResult = (new DataBase())->select("SELECT * FROM " . self::TABLE_NAME . " WHERE idUser = $idUser");

				if (isset($data["Sort"]))
				{
					switch ($data["Sort"])
					{
						case "Notes":
							$statement = (new DataBase())->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE idUser = $idUser ORDER BY note " . $orderBy);
							$statement->execute();
							$statementResult = $statement->fetchAll(PDO::FETCH_ASSOC);
							break;
					}
				}
				else
				{
					$statement = (new DataBase())->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE idUser = $idUser");
					$statement->execute();
					$statementResult = $statement->fetchAll(PDO::FETCH_ASSOC);
				}

				if ($statementResult)
				{
					return new ApiValue($statementResult);
				}
				else
				{
					return new ApiValue();
				}
			}
			catch (Exception $exists)
			{
				return new ApiValue(null, $exists->getMessage(), $exists->getCode());
			}
		}

		return new ApiValue(null, "invalid token", "401");
	}

	/**
	 * Update a note
	 * @return ApiValue|string
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function updateNote() : ApiValue|string
	{
		$token = ModelMain::getAuthorization();

		if (ModelUsers::isValidTokenAccount($token))
		{
			$data = ModelMain::getBody();
			$idUser = self::getIdUser($token);
			$idNote = $data['idNote'];
			unset($data['idNote']);

			try
			{
				(new DataBase())->update(self::TABLE_NAME, $data, "idUser = $idUser AND idNote = $idNote");
				return new ApiValue(null, "The note has been edited");
			}
			catch (Exception $e)
			{
				return new ApiValue(null, $e->getMessage(), $e->getCode());
			}
		}

		return "invalid token";
	}

	/**
	 * Delete a note
	 * @return ApiValue|string
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function deleteNote() : ApiValue|string
	{
		$token = ModelMain::getAuthorization();

		if (ModelUsers::isValidTokenAccount($token))
		{
			$data = ModelMain::getBody();
			$idNote = $data['idNote'];
			unset($data['idNote']);

			$idUser = self::getIdUser($token);

			try
			{
				(new DataBase())->delete(self::TABLE_NAME, "idUser = $idUser AND idNote = $idNote")->execute();
				return new ApiValue(null, "The note has been deleted");
			}
			catch (Exception $e)
			{
				return new ApiValue(null, $e->getMessage(), $e->getCode());
			}
		}

		return "invalid token";
	}
}