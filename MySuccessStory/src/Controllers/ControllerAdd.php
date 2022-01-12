<?php

namespace MySuccessStory\Controllers;

use MySuccessStory\Api\Model\Functions;
use MySuccessStory\Api\Model\Note;

class ControllerAdd
{
    /**
     * Add a note on the data base
     *
     * @return void
     * @author Almeida Costa Lucas lucas.almdc@eduge.ch / flavio.srsrd@eduge.ch
     */
    public function addNote()
    {
        // Cookie
        $functions = new Functions();
        $functionsNotes = new Note();

        if (!isset($_COOKIE['email'])) {
            header("Location:http://mysuccessstory/");
        }

        if ($functions->refreshCookie()) {
            $emailParts = explode(".", $_COOKIE['email']);
            // Get subjects from the database
            $subjects = $functions->curl("http://mysuccessstory/api/subjects");
            $years = $functions->curl("http://mysuccessstory/api/year");
            $idUsers = $functions->curl("http://mysuccessstory/api/userID/$emailParts[0]/$emailParts[1]");

            $note = filter_input(INPUT_POST, 'note', FILTER_VALIDATE_FLOAT);
            $sub = filter_input(INPUT_POST, 'subjects', FILTER_SANITIZE_STRING);
            $year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_STRING);
            $semester = filter_input(INPUT_POST, 'semester', FILTER_VALIDATE_INT);
            $submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_STRING);

            // var_dump(
            //     $note,
            //     $sub,
            //     $semester,
            //     $idUsers[0]->idUser,
            //     $year
            // );
            if ($submit == "Ajouter") {
                if ($note >= 1.0 && $note <= 6.0 && fmod($note, 0.5) == 0) {
                    $functionsNotes->addNote($note, $idUsers[0]->idUser,  $sub, $semester, $year);
                    // header("Location:http://mysuccessstory/profile");
                } else {
                    return "marche pas";
                    $_POST["note"] = "";
                }
            }
        }

        require '../src/view/viewAddNote.php';
    }
}
