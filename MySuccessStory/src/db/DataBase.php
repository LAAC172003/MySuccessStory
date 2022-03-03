<?php

namespace MySuccessStory\db;
use PDO;
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
			$dbDsn = "mysql:host=".$dbData->host.";port=".$dbData->port.";dbname=".$dbData->database ?? '';
			$username = $dbData->user ?? '';
			$password = $dbData->password ?? '';

			$this->pdo = new PDO($dbDsn, $username, $password);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
	}

	public function prepare($sql): \PDOStatement
	{
		return $this->pdo->prepare($sql);
	}

	public function insert($tableName, $attributes)
	{
		$params = array_map(fn($attr) => ":$attr", $attributes);
		$statement = self::prepare("INSERT INTO $tableName (" . implode(",", $attributes) . ") VALUES (" . implode(",", $params) . ")");
		foreach ($attributes as $attribute) {
			$statement->bindValue(":$attribute", $this->{$attribute});
		}
		$statement->execute();
		return true;
	}

	public function update($tableName, $data, $where = '')
	{
		$whereSql = "";
		if (!empty($where)) {
			if (!str_starts_with(strtoupper(trim($where)), 'WHERE')) {
				$whereSql = " WHERE " . $where;
			} else {
				$whereSql = " " . strtoupper(trim($where));
			}
		}
		$query = "UPDATE `" . $tableName . "` SET ";
		$sets = array();

		foreach ($data as $column => $value) {
			$sets[] = "`" . $column . "` = '" . $value . "'";
		}
		$query .= implode(', ', $sets);
		$query .= $whereSql;
		var_dump($query);
		return $this->prepare($query)->execute();
	}

	public function delete($tableName, $where="")
	{
		$whereSql = '';

		if (!empty($where)) {
			// check to see if the 'where'
			if (!str_starts_with(strtoupper(trim($where)), 'WHERE')) {
				// not found, add keyword
				$whereSql = " WHERE " . $where;
			} else {
				$whereSql = " " . strtoupper(trim($where));
			}
		}
		// build the query
		$sql = "DELETE FROM " . $tableName . $whereSql;
		return self::prepare($sql);
	}
}