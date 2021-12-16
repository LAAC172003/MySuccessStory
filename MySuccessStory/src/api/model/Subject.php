<?php

namespace MySuccessStory\Api\Model;

/**
 * Return an array of subjects in json
 *
 * @param class $db
 * @param string $sql
 * @return array return an array of notes in json
 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
 */
class Subject
{
    //return all the subjects
    public static function getSubjects($db, $sql)
    {
        $getSubjects = $db->query("$sql");
        $GLOBALS["subjects"] = $getSubjects->fetchAll();
        $db->close();
        echo json_encode($GLOBALS["subjects"], JSON_UNESCAPED_UNICODE);
    }
}
