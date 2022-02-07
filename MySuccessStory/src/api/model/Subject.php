<?php

namespace MySuccessStory\Api\Model;

class Subject
{
    /**
     * Return an array of subjects in json
     *
     * @param class $db
     * @param string $sql
     * 
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
    */
    public static function getSubjects($db, $sql)
    {
        $getSubjects = $db->query("$sql");
        $GLOBALS["subjects"] = $getSubjects->fetchAll();
        $db->close();
        echo json_encode($GLOBALS["subjects"], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Return an array in json of one subject by the idNote
     *
     * @param int $idNote
     * @return array Return an array in json
     * 
     * @author Soares Rodrigues Flavio <flavio.srsrd@eduge.ch>
    */
    public static function getSubjectByIdNote($idNote)
    {
        $db = new SqlConnection(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $subject = $db->query(
            "SELECT `subject`.`idSubject`, `subject`.`name`, `subject`.`description`, `subject`.`isCIE`, `subject`.`idYear`, `subject`.`idCategory`
            FROM `subject`
            INNER JOIN `note` ON `subject`.`idSubject` = `note`.`idSubject`
            WHERE `note`.`idNote` = $idNote
        ");
        return $subject->fetchAll(json_encode($subject, JSON_UNESCAPED_UNICODE))[0];
    }
}
