<?php

namespace MySuccessStory\API\Model;

class User
{
    public static function getUserByEmail($db, $sql)
    {
        $getSubjects = $db->query("$sql");
        $GLOBALS["user"] = $getSubjects->fetchAll();
        if ($GLOBALS['user']->rowCount > 0) {
            $db->close();
            echo json_encode($GLOBALS["user"], JSON_UNESCAPED_UNICODE);
        } else {
            echo "Vous n'avez pas renseigné le bon email !";
        }
    }
}
