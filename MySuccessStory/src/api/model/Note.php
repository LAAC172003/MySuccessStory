<?php

namespace MySuccessStory\Api\Model;

class Note
{
    //return all the notes
    public static function getNotes($db, $sql)
    {
        $getNotes = $db->query("$sql");
        $GLOBALS["notes"] = $getNotes->fetchAll();
        $db->close();
        echo json_encode($GLOBALS["notes"], JSON_UNESCAPED_UNICODE);
    }
}
