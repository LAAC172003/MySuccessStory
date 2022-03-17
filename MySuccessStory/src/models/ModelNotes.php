<?php

namespace MySuccessStory\models;

use MySuccessStory\db\DataBase;
use PDO;
use Exception;

class ModelNotes
{
	/**
	 * Name of the table for the mysql request
	 */
	const TABLE_NAME = "notes";

	/**
	 * Create a note
	 * @return ApiValue
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
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
			if (!$idSubject) return ModelMain::checkHttpCode(new ApiValue(null, "Le sujet n'est pas correct", "400"));
			if ($data['semester'] > 2) return ModelMain::checkHttpCode(new ApiValue(null, "Il n'y a que 2 semestres", "400"));
			if ($data['note'] > 6) return ModelMain::checkHttpCode(new ApiValue(null, "la note maximale est de 6", "400"));

			try
			{
				(new DataBase())->insert(self::TABLE_NAME, $data);
				return new ApiValue($data);
			}
			catch (Exception $e)
			{
				if (!ModelMain::checkSqlError($e->getCode()))
				{
					return new ApiValue(null, "Syntax error : the sent body is not a valid JSON object", "400");
				}

				return new ApiValue(null, $e->getMessage(), $e->getCode());
			}
		}

		return ModelMain::checkHttpCode(new ApiValue(null, "invalid token", "403"));
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
	public static function readNote(): ApiValue|string
	{
		$token = ModelMain::getAuthorization();

		if ($token->errorCode != "")
		{
			return ModelMain::checkHttpCode(new ApiValue(null, "Authentification error : You need a bearer token to send a request", "401"));
		}

		if (ModelUsers::isValidTokenAccount($token))
		{
			$data = ModelMain::getBody();
			$idUser = self::getIdUser($token);

			try
			{
				ModelMain::checkPrimaryKey($data, "idNote");

				if (is_object($data))
				{
					$exists = property_exists($data, "idNote");
				}
				else
				{
					$exists = isset($data);
				}

				if ($exists)
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

					if (isset($data["Period"]))
					{
						$period = strtolower($data["Period"]);

						switch ($period)
						{
							case "semester":
								$year = isset($data["Year"]) ? $data["Year"] : 1;
								$semester = isset($data["Semester"]) ? $data["Semester"] : 1;

								$query = "SELECT * FROM " . self::TABLE_NAME . " WHERE idYear = $year AND semester = $semester";
								$mode = PDO::FETCH_ASSOC;
								break;
							case "year":
								$year = isset($data["Year"]) ? $data["Year"] : 1;

								$query = "SELECT * FROM " . self::TABLE_NAME . " WHERE idYear = $year";
								$mode = PDO::FETCH_ASSOC;
								break;
							case "Formation":
							default:
								$query = "SELECT * FROM " . self::TABLE_NAME;
								$mode = PDO::FETCH_ASSOC;
								break;
						}
					}
					else
					{
						if (isset($data["idNote"]))
						{
							$query = "SELECT * FROM " . self::TABLE_NAME . " WHERE idNote = " . $data["idNote"];
							$mode = PDO::FETCH_OBJ;
						}
						else if (isset($data["Sort"]))
						{
							switch (strtolower($data["Sort"]))
							{
								case "notes":
									$query = "SELECT * FROM " . self::TABLE_NAME . " ORDER BY note " . $orderBy;
									$mode = PDO::FETCH_ASSOC;
									break;
								case "subject":
									$query = "SELECT * FROM " . self::TABLE_NAME . " ORDER BY idSubject " . $orderBy;
									$mode = PDO::FETCH_ASSOC;
									break;
							}
						}
						else
						{
							$query = "SELECT * FROM " . self::TABLE_NAME;
							$mode = PDO::FETCH_ASSOC;
						}
					}
				}
				else
				{
					$query = "SELECT * FROM " . self::TABLE_NAME;
					$mode = PDO::FETCH_ASSOC;
				}

				$statement = (new DataBase())->prepare($query);
				$statement->execute();
				$statementResult = $statement->fetchAll($mode);

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

		return new ApiValue(null, "Invalid token", "403");
	}

	/**
	 * Update a note
	 * @return ApiValue|string
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 */
	public static function updateNote(): ApiValue|string
	{
		$token = ModelMain::getAuthorization();

		if (ModelUsers::isValidTokenAccount($token))
		{
			$idUser = self::getIdUser($token);
			$data = ModelMain::getBody();

			try
			{
				ModelMain::checkPrimaryKey($data, "idNote");

				(new DataBase())->update(self::TABLE_NAME, $data, "idNote = " . $data['idNote']);

				return new ApiValue($data);
			}
			catch (Exception $e)
			{
				if (!ModelMain::checkSqlError($e->getCode()))
				{
					return new ApiValue(null, "Syntax error : the sent body is not a valid JSON object", "400");
				}

				return new ApiValue(null, $e->getMessage(), $e->getCode());
			}
		}

		return new ApiValue(null, "invalid token", "403");
	}

	/**
	 * Delete a note
	 * @return ApiValue|string
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function deleteNote(): ApiValue|string
	{
		$token = ModelMain::getAuthorization();

		if (ModelUsers::isValidTokenAccount($token))
		{
			$idUser = self::getIdUser($token);
			$data = ModelMain::getBody();

			try
			{
				(new DataBase())->delete(self::TABLE_NAME, "idUser = $idUser")->execute();
				return new ApiValue($data);
			}
			catch (Exception $e)
			{
				if (!ModelMain::checkSqlError($e->getCode()))
				{
					return new ApiValue(null, "Syntax error : the sent body is not a valid JSON object", "400");
				}

				return new ApiValue(null, $e->getMessage(), $e->getCode());
			}
		}

		return new ApiValue(null, "invalid token", "403");
	}

	/**
	 * Calculates the pass mark to success the CFC
	 *
	 * @param float $tpi
	 * @param float $cbe
	 * @param float $ci
	 * @param float $cg
	 * @return float returns result
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function cfcAverage(float $tpi, float $ci, float $cg, float $cbe): float
	{
		return 0.3 * $tpi + 0.2 * $cbe + 0.3 * $ci + 0.2 * $cg;
	}

	/**
	 * calculates the pass mark of 8 values
	 *
	 * @param array $notes
	 * @return float|int returns the result of the average of all the notes
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function average(array $notes): float|int
	{
		if ($notes[0] == null) {
			return $result = 4;
		} else {
			$result = 0.0;
			for ($i = 0; $i < count($notes[0]); $i++) {
				$result += $notes[0][$i]->note;
			}
			return ($result / count($notes[0]));
		}
	}

	/**
	 * calculates an average
	 *
	 * @param array $notes
	 * @return float|int result
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function calculate(array $notes): float|int
	{
		if ($notes[0] == null) {
			return $result = 4;
		} else {
			$result = $notes[0] / 5;
			return round($result * 2) / 2;
		}
	}

	/**
	 * calculates the average of the 4 subjects to success the CBE
	 *
	 * @param float $english
	 * @param float $economy
	 * @param float $maths
	 * @param float $physics
	 * @return float|int returns the rounded result
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function cbeNote(float $english, float $economy, float $maths, float $physics): float|int
	{

		$result = (round($english * 2) / 2 + round($economy * 2) / 2 + round($maths * 2) / 2 + round($physics * 2) / 2) / 4;
		return round($result * 2) / 2;
	}

	/**
	 * calculates the note of the ci
	 *
	 * @param float $cie
	 * @param float $school
	 * @return float return the result of the CI
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function ciNote(float $cie, float $school): float
	{
		return 0.8 * $school + 0.2 * $cie;
	}
}