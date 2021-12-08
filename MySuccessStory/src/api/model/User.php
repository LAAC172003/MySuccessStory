<?php

namespace MySuccessStory\api\model;

class User
{
    public static function getUserByEmail($db, $sql)
    {
        $getSubjects = $db->query("$sql");
        $GLOBALS["user"] = $getSubjects->fetchAll();
        if (count($GLOBALS['user']) > 0) {
            $db->close();
            echo json_encode($GLOBALS["user"], JSON_UNESCAPED_UNICODE);
        } else {
            echo "Vous n'avez pas renseigné le bon email !";
        }
    }
    public function createNewAccount($email, $pwd, $firstName, $lastName)
    {
        $db = new SqlConnection(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $compte = $db->query('INSERT INTO user (email,password,firstName,lastName) VALUES (?,?,?,?)', $email, $pwd, $firstName, $lastName);
    }
    public function emailUsers($db, $sql)
    {
        $getSubjects = $db->query("$sql");
        $GLOBALS["user"] = $getSubjects->fetchAll();
        $db->close();
        echo json_encode($GLOBALS["user"], JSON_UNESCAPED_UNICODE);
    }
}
