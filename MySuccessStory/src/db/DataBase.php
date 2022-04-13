<?php

namespace MySuccessStory\db;

use PDO;
use PDOStatement;
use Throwable;

/**
 * Class used to connect to the db
 * @author Almeida Costa Lucas
 */
class DataBase
{
	public PDO $pdo;

	public function __construct()
	{
		$dbData = json_decode(file_get_contents("../src/db/DbConfig.json"));

		if ($dbData)
		{
			$dbDsn = "mysql:host=" . $dbData->host . ";port=" . $dbData->port . ";dbname=" . $dbData->database ?? '';
			$username = $dbData->user ?? '';
			$password = $dbData->password ?? '';

			$this->pdo = new PDO($dbDsn, $username, $password);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
	}

	public function prepare($sql) : PDOStatement
	{
		return $this->pdo->prepare($sql);
	}

	public function select($sql)
	{
		$statement = self::prepare($sql);
		$statement->execute();
		return $statement->fetchAll(PDO::FETCH_OBJ);
	}

	public function insert($tableName, $data)
	{
		$fields = array_keys($data);
		$query = "INSERT INTO `" . $tableName . "` (`" . implode('`,`', $fields) . "`) VALUES('" . implode("','", $data) . "')";

		return self::prepare($query)->execute();
	}

	public function update($tableName, $data, $where = '')
	{
		if (count($data) == 0) return true;

		$statement = self::prepare("DESCRIBE $tableName");
		$statement->execute();
		$temp = $statement->fetchAll(PDO::FETCH_ASSOC);

		foreach ($data as $field => $value)
		{
			if (is_null($field))
			{
				unset($data[$field]);
			}
			else
			{
				$inArray = false;

				foreach ($temp as $infos)
				{
					if ($field == $infos["Field"])
					{
						$inArray = true;
						break;
					}
				}

				if (!$inArray) unset($data[$field]);
			}
		}

		$whereSql = "";

		if (!empty($where))
		{
			if (!str_starts_with(strtoupper(trim($where)), 'WHERE'))
			{
				$whereSql = " WHERE " . $where;
			}
			else
			{
				$whereSql = " " . strtoupper(trim($where));
			}
		}

		$query = "UPDATE `" . $tableName . "` SET ";
		$sets = array();

		foreach ($data as $column => $value)
		{
			$sets[] = "`" . $column . "` = '" . $value . "'";
		}

		$query .= implode(', ', $sets);
		$query .= $whereSql;

		return self::prepare($query)->execute();
	}

	public function delete($tableName, $where = "")
	{
		$whereSql = '';

		if (!empty($where))
		{
			// check to see if the 'where'
			if (!str_starts_with(strtoupper(trim($where)), 'WHERE'))
			{
				// not found, add keyword
				$whereSql = " WHERE " . $where;
			}
			else
			{
				$whereSql = " " . strtoupper(trim($where));
			}
		}

		// build the query
		$sql = "DELETE FROM " . $tableName . $whereSql;
		return self::prepare($sql);
	}
}