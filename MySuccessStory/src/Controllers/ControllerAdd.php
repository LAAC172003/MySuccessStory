<?php

namespace MySuccessStory\Controllers;

use MySuccessStory\Api\Model\Functions;
use MySuccessStory\Api\Model\Note;

/**
 * method who add a note
 *
 * @author Soares Rodrigues Flavio <flavio.srsrd@eduge.ch>
 */
class ControllerAdd
{
    /**
     * Add a note on the data base
     *
     * @return void
     * @author Almeida Costa Lucas lucas.almdc@eduge.ch
     * @author Soares Flavio flavio.srsrd@eduge.ch
     */
    public function addNote()
    {
        // Cookie 
        $functions = new Functions();

        // Note object
        $functionsNotes = new Note();

        // Redirect to the home page if not logged
        $functions->redirectIfNotLogged();

        if ($functions->refreshCookie())
        {
            $emailParts = explode(".", $_COOKIE['email']);

            // Get data from api
            $subjects = $functions->curl("http://mysuccessstory/api/subjects");
            $years = $functions->curl("http://mysuccessstory/api/year");
            $idUsers = $functions->curl("http://mysuccessstory/api/userID/$emailParts[0]/$emailParts[1]");

            $note = filter_input(INPUT_POST, 'note', FILTER_VALIDATE_FLOAT);
            $sub = filter_input(INPUT_POST, 'subjects', FILTER_SANITIZE_SPECIAL_CHARS);
            $year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_SPECIAL_CHARS);
            $semester = filter_input(INPUT_POST, 'semester', FILTER_VALIDATE_INT);
            $submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_SPECIAL_CHARS);

            if ($submit == "Ajouter")
            {
                if ($note >= 1.0 && $note <= 6.0 && fmod($note, 0.5) == 0)
                {
                    $functionsNotes->addNote($note, $idUsers[0]->idUser,  $sub, $semester, $year);

                    // Redirect the user to profile
                    $functions->redirect("profile");
                }
                else if ($note < 1.0 && $note > 6.0)
                {
                    echo ("erreur de saisie : la note n'est pas comprise entre 1 et 6");
                }
                else
                {
                    echo ("erreur de saisie : la note doit contenir un chiffre entre 1 et 6");
                }
            }
            else if($submit == "Annuler")
            {
                $functions->redirect("profile");
            }
        }
        require '../src/view/viewAddNote.php';
    }
}