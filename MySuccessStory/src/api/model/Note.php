<?php

namespace MySuccessStory\Api\Model;

use MySuccessStory\Api\Model\SqlConnection;

class Note
{
    /**
     * Return an array of notes in json
     *
     * @param class $db
     * @param string $sql
     * @return string array of notes in json
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public static function notes($db, $sql)
    {
        $notes = $db->query("$sql");
        $GLOBALS["notes"] = $notes->fetchAll();
        $db->close();
        return json_encode($GLOBALS["notes"], JSON_UNESCAPED_UNICODE);
    }
    
    public function getSubjectByCategory($category)
    {
        return "SELECT idSubject,s.name,c.name AS 'category' FROM subject s INNER JOIN category c ON s.idCategory = c.idCategory WHERE c.name = '$category'";
    }

    /**
     * Return in json an array with all data of one note
     *
     * @param int $idNote
     * @return string array of notes in json
     * @author Flavio Soares Rodrigues <flavio.srsrd@eduge.ch>
    */
    public static function getNoteById($idNote)
    {
        $db = new SqlConnection(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $note = $db->query(
            "SELECT `idNote`, `note`, `semester`, `idUser`, `idSubject`, `idYear`
            FROM `note`
            WHERE `idNote` = '$idNote'
        ");
        return $note->fetchAll(json_encode($note, JSON_UNESCAPED_UNICODE))[0];
    }

    /**
     * Insert a new note on the database with 5 params
     *
     * @param float $note number from 1 to 6 rounded to the half
     * @param int   $idUser
     * @param int   $idSubject
     * @param int   $semester
     * @param int   $idYear
     *
     * @author Almeida Costa Lucas
     */
    public static function addNote($note, $idUser, $subject, $semester, $year)
    {
        $db = new SqlConnection(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $add = $db->query(
            "INSERT INTO `note`(
                `note`,
                `semester`,
                `idUser`,
                `idSubject`,
                `idYear`
            )
            VALUES(
                $note,
                $semester,
                (
                SELECT
                    `idUser`
                FROM
                    `user`
                WHERE
                    `idUser` = $idUser
            ),
            (
                SELECT
                    `idSubject`
                FROM
                    `subject`
                WHERE
                    `name` = '$subject'
            ),
            (
                SELECT
                    idYear
                FROM
                    `year`
                WHERE
                    `year` = '$year'
            )
            )
        "
        );
    }

    /**
     * update function
     *
     * @param int $note
     * @param string $semester
     * @param int $idSubject
     * @param int $idNote
     * @return sql query
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function update($note, $semester, $idSubject, $idNote, $idYear)
    {
        $db = new SqlConnection(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        return $update = $db->query("UPDATE `note` SET `idNote`= $idNote, `note` = $note, `semester`= $semester, `idSubject` = $idSubject, `idYear` = $idYear WHERE idNote = $idNote");
        // header("Location:http://mysuccessstory/");
    }


    /**
     * delete an element of the note by a the id
     *
     * @param int $id
     * @return bool return true if the query is successful return false if it's not
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function delete($id)
    {
        $db = new SqlConnection(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        return $delete = $db->query("DELETE FROM `note` WHERE idNote =  $id");
    }

    /**
     * calculates the pass mark to success the CFC
     *
     * @param float $tpi
     * @param float $cbe
     * @param float $ci
     * @param float $cg
     * @return float returns result
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function passMarkCFC($tpi, $cbe, $ci, $cg)
    {
        return 0.3 * $tpi + 0.2 * $cbe + 0.3 * $ci + 0.2 * $cg;
    }

    /**
     * calculates the pass mark of 8 values
     *
     * @param array $notes
     * @return float returns the result of the average of all the notes
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function passMark(array $notes)
    {
        if ($notes[0] == null) {
            return $result = 4;
        } else {
            $result = 0.0;

            for ($i = 0; $i < count($notes[0]); $i++) {
                $result += $notes[0][$i]->note;
            }

            return ($result / count($notes[0]));
        }
    }

    /**
     * calculates an average
     *
     * @param array $notes
     * @return float result
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function calculate(array $notes)
    {
        if ($notes[0] == null) {
            return $result = 4;
        } else {
            $result =  $notes[0] / 5;
            // var_dump($notes);
            return round($result * 2) / 2;
        }
    }
    public function AverageSimulation(array $notes)
    {
        if ($notes[0] == null) {
            return $result = 4;
        } else {
            $result =  $notes[0] / 4;
            return round($result * 2) / 2;
        }
    }

    /**
     * calculates the average of the 4 subjects to success the CBE
     *
     * @param float $english
     * @param float $economy
     * @param float $maths
     * @param float $physics
     * @return float returns the rounded result
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function noteCBE($english, $economy, $maths, $physics)
    {

        $result = (round($english * 2) / 2 + round($economy * 2) / 2 + round($maths * 2) / 2 + round($physics * 2) / 2) / 4;
        return round($result * 2) / 2;
    }

    /**
     * calculates the note of the ci
     *
     * @param float $cie
     * @param float $school
     * @return float return the result of the CI
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function noteCI($cie, $school)
    {
        return $result = 0.8 * $school + 0.2 * $cie;
    }
}