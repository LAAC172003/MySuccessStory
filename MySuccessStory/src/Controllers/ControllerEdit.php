<?php

namespace MySuccessStory\Controllers;

use MySuccessStory\Api\Model\Functions;

class ControllerEdit
{
    /**
     * Modify a note on the data base
     *
     * @return void
     * @author Almeida Costa Lucas lucas.almdc@eduge.ch
     * @author Soares Flavio flavio.srsrd@eduge.ch
    */
    public function editNote()
    {
        //if a user is logged
        if (!isset($_COOKIE['email'])) {
            header("Location:http://mysuccessstory/");
        }

        // Cookie 
        $functions = new Functions();
        $functionsNotes = new Note();

        $emailParts = explode(".", $_COOKIE['email']);
        $semesters = [1, 2];

        if ($functions->refreshCookie()) {
            // Get data from api
            $subjects = $functions->curl("http://mysuccessstory/api/subjects");
            $years = $functions->curl("http://mysuccessstory/api/year");
            $idUsers = $functions->curl("http://mysuccessstory/api/userID/$emailParts[0]/$emailParts[1]");
            $notes = $functions->curl("http://mysuccessstory/api/notes/$emailParts[0]/$emailParts[1]");
        }
        var_dump($notes[0]);
        require '../src/view/viewEdit.php';
    }
}
            $note = filter_input(INPUT_POST, 'note', FILTER_VALIDATE_FLOAT);
            $sub = filter_input(INPUT_POST, 'subjects', FILTER_SANITIZE_STRING);
            $year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_STRING);
            $semester = filter_input(INPUT_POST, 'semester', FILTER_VALIDATE_INT);
            $submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_STRING);

            if ($submit == "Ajouter") {
                if ($note >= 1.0 && $note <= 6.0 && fmod($note, 0.5) == 0) {
                    $functionsNotes->addNote($note, $idUsers[0]->idUser,  $sub, $semester, $year);
                    header("Location:http://mysuccessstory/profile");
                } else {
                    return "marche pas";
                }
                if ("test") {
                    # code...
                }
            }
        }
        require '../src/view/viewAddNote.php';
    }