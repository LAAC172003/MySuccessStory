<?php

namespace MySuccessStory\api\model;

class Subjects
{
    public static function getSubjects($db, $sql)
    {
        $getSubjects = $db->query("$sql");
        $GLOBALS["subjects"] = $getSubjects->fetchAll();
        $db->close();
        echo json_encode($GLOBALS["subjects"], JSON_UNESCAPED_UNICODE);
        
        // return $result;
    }
}
