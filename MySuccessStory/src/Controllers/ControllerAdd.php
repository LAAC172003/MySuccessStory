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
     * @author Flavio Soares Rodrigues flavio.srsrd@eduge.ch
     */
    public function addNote()
    {
        // Cookie
        $functions = new Functions();
        
        if ($functions->refreshCookie())
        {
            // Get subjects from the database
            $subjects = $functions->curl("http://mysuccessstory/api/subjects");

            $note = filter_input(INPUT_POST, 'note', FILTER_VALIDATE_FLOAT);
            $idSubject = filter_input(INPUT_POST, 'subjects', FILTER_VALIDATE_INT);
            $idYear = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
            $semester = filter_input(INPUT_POST, 'semester', FILTER_SANITIZE_STRING);
            $submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_STRING);

            if ($submit == "Ajouter")
            {
                if ($note >= 1.0 && $note <= 6.0 && fmod($note, 0.5) == 0)
                {
                    // idUser a rajouter
                    Note::addNote($note, $semester, 1, $idSubject, $idYear);
                }
                else
                {
                    echo "marche pas";
                    $_POST["note"] = "";
                }
            }
        }
        require '../src/view/viewAddNote.php';
    }
}