<?php

namespace MySuccessStory\db;
/**
 * Class used to connect to the db
 * @author Almeida Costa Lucas
 */
class DataBase
{
    public \PDO $pdo;

    public function __construct()
    {
        $dbDsn = "mysql:host=10.5.42.2;port=3306;dbname=mysuccessstory" ?? '';
        $username = "mysuccessstory" ?? '';
        $password = "&4k@tbjLDK" ?? '';


        $this->pdo = new \PDO($dbDsn, $username, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function prepare($sql): \PDOStatement
    {
        return $this->pdo->prepare($sql);
    }

    public function insert($tableName, $data)
    {
        $fields = array_keys($data);
        $query = "INSERT INTO `" . $tableName . "` (`" . implode('`,`', $fields) . "`) VALUES('" . implode("','", $data) . "')";
        return self::prepare($query)->execute();
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
        return self::prepare($query)->execute();
    }

    public function delete($tableName, $where = "")
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