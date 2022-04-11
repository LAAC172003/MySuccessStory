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
	 * @return ApiValue
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function createNote() : ApiValue
	{
		$token = ModelMain::getAuthorization();

		if ($token->value != null)
		{
			if (ModelMain::checkToken($token->value))
			{
				$data = ModelMain::getBody();
				$idUser = ModelMain::getIdUser($token->value);
				$data["idUser"] = $idUser;

				$subject = $data["idSubject"];
				$idSubject = (new DataBase())->select("SELECT idSubject FROM subjects WHERE name = '$subject'");

				if (!$idSubject) return new ApiValue(null, "The subject is incorrect", "400");

				$data["idSubject"] = $idSubject[0]->idSubject;

				if ($data["note"] < 1) return new ApiValue(null, "The note can't be below 1", "400");
				if ($data["note"] > 6) return new ApiValue(null, "The note can't be over 6", "400");
				if (fmod($data["note"], 0.5) != 0) return new ApiValue(null, "The note has to be a multiple of 0.5", "400");
				if ($data["semester"] != 1 && $data["semester"] != 2) return new ApiValue(null, "The semester has to be 1 or 2", "400");

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
			return new ApiValue(null, "invalid token", "401");
		}
		else
		{
			return new ApiValue(null, "no authentication token", "401");
		}
	}

	/**
	 * Read a note
	 * @return ApiValue
	 * @author Jordan Folly <ekou-jordan.fllsd@eduge.ch>
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function readNote() : ApiValue
	{
		$token = ModelMain::getAuthorization();

		if ($token->value != null)
		{
			if (ModelMain::checkToken($token->value))
			{
				$data = ModelMain::getBody();
				$idUser = ModelMain::getIdUser($token->value);

				try
				{
					$orderBy = "ASC";

					if (isset($data["Id"]))
					{
						$idNote = $data["Id"];
						$statement = (new DataBase())->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE idUser = $idUser AND idNote = $idNote ORDER BY note " . $orderBy);
						$statement->execute();
						$statementResult = $statement->fetchAll(PDO::FETCH_ASSOC);
					}
					else
					{
						$queryString = "SELECT * FROM " . self::TABLE_NAME . " WHERE idUser = $idUser";

						if (isset($data["Note"]))
						{
							$note = $data["Note"];

							if ($note < 1) return new ApiValue(null, "The note can't be below 1", "400");
							if ($note > 6) return new ApiValue(null, "The note can't be over 6", "400");
							if (fmod($note, 0.5) != 0) return new ApiValue(null, "The note has to be a multiple of 0.5", "400");

							$queryString .= " AND note = $note";
						}

						if (isset($data["Semester"]))
						{
							$semester = $data["Semester"];

							if ($semester != 1 && $semester != 2) return new ApiValue(null, "The semester has to be 1 or 2", "400");

							$queryString .= " AND semester = $semester";
						}

						if (isset($data["Year"]))
						{
							$year = $data["Year"];

							if (!is_int($year)) return new ApiValue(null, "The year has to be an integer number", "400");
							if ($year < 1 || $year > 4) return new ApiValue(null, "The year has to be between 1 and 4", "400");

							$queryString .= " AND year = $year";
						}

						if (isset($data["Subject"]))
						{
							$subject = $data["Subject"];

							if ($subject == "") return new ApiValue(null, "The subject can't be empty", "400");

							$statement = (new DataBase())->prepare("SELECT idSubject FROM subjects WHERE name = '$subject'");
							$statement->execute();
							$statementResult = $statement->fetchAll(PDO::FETCH_ASSOC);

							if (isset($statementResult[0]))
							{
								$idSubject = $statementResult[0]["idSubject"];
							}
							else
							{
								return new ApiValue(null, "The subject doesn't exist", "400");
							}

							$queryString .= " AND idSubject = $idSubject";
						}

						if (isset($data["Sort"]))
						{
							switch ($data["Sort"])
							{
								case "Note":
									$queryString .= " ORDER BY note " ;
									break;
								case "Subject":
									$queryString .= " ORDER BY idSubject " ;
									break;
								default:
									return new ApiValue(null, "The only avaible sorts are by Note and Subject. ", "400");
									break;
							}
						}

						if (isset($data["Order"]))
						{
							if (strtoupper($data["Order"]) == "ASC" || strtoupper($data["Order"]) == "DESC")
							{
								$queryString .= " " . strtoupper($data["Order"]);
							}
						}

						$statement = (new DataBase())->prepare($queryString);
						$statement->execute();
						$statementResult = $statement->fetchAll(PDO::FETCH_ASSOC);
					}

					return new ApiValue($statementResult);
				}
				catch (Exception $exists)
				{
					return new ApiValue(null, $exists->getMessage(), $exists->getCode());
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
	 * Update a note
	 * @return ApiValue
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function updateNote() : ApiValue
	{
		$token = ModelMain::getAuthorization();

		if ($token->value != null)
		{
			if (ModelMain::checkToken($token->value))
			{
				$data = ModelMain::getBody();
				$idUser = ModelMain::getIdUser($token->value);
				$idNote = $data['idNote'];
				unset($data['idNote']);

				try
				{
					$pdo = new DataBase();

					$pdo->update(self::TABLE_NAME, $data, "idUser = $idUser AND idNote = $idNote");

					$statement = $pdo->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE idNote = $idNote");
					$statement->execute();

					return new ApiValue($statement->fetchAll(PDO::FETCH_ASSOC), "The note has been edited");
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
	 * Delete a note
	 * @return ApiValue
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function deleteNote() : ApiValue
	{
		$token = ModelMain::getAuthorization();

		if ($token->value != null)
		{
			if (ModelMain::checkToken($token->value))
			{
				$data = ModelMain::getBody();
				$idNote = $data["idNote"];
				unset($data["idNote"]);

				$idUser = ModelMain::getIdUser($token->value);

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
		}
		else
		{
			return new ApiValue(null, "no authentication token", "401");
		}

		return new ApiValue(null, "invalid token", "401");
	}
}