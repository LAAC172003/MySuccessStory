<?php

namespace MySuccessStory\Api\Model;

use MySuccessStory\Api\Model\SqlConnection;

class Note
{
    /**
     * Insert a new note on the database with 4 params
     * 
     * @param float $note is a float "0.5" between 1 and 6
     * @param int   $idUser
     * @param int   $idSubject
     * @param int   $idPeriod
     * 
     * @author Flavio Soares Rodrigues
     */
    public function addNote($note, $idUser, $idSubject, $semester, $year)
    {
        $db = new SqlConnection(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $add = $db->query(
            "INSERT INTO `note`(
                `note`,
                `idUser`,
                `idSubject`,
                `idPeriod`
            ) 
            VALUES
            (   
                $note,
                (
                    SELECT  `idUser`
                    FROM    `user`
                    WHERE   `idUser` = $idUser
                ),
                (
                    SELECT  `idSubject`
                    FROM    `subject`
                    WHERE   `idSubject` = $idSubject
                ),
                (
                    SELECT  `idPeriod`
                    FROM    `period`
                    WHERE   `semester` = $semester AND `year` = $year
                )
            )
        "
        );
    }
    public static function notes($db, $sql)
    {
        $notes = $db->query("$sql");
        $GLOBALS["notes"] = $notes->fetchAll();
        $db->close();
        echo json_encode($GLOBALS["notes"], JSON_UNESCAPED_UNICODE);
    }

    public function update($element, $value, $id)
    {
        $db = new SqlConnection(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $update = $db->query("UPDATE `note` SET $element = $value WHERE idNote = $id");
        //UPDATE utilisateurs SET nomFamille = :nomFamille, prenom = :prenom WHERE idUtilisateur = :idUtilisateur
    }

    public function delete($id)
    {
        $db = new SqlConnection(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $delete = $db->query("DELETE FROM `note` WHERE idNote =  $id");
    }
}
