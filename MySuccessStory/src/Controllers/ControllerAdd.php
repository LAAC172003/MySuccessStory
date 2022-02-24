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
        session_start();

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
                    if (isset($_POST["fakeNote"]))
                    {
                        if ($_POST["fakeNote"])
                        {
                            if (!isset($_SESSION["fakeNotes"]))
                            {
                                $_SESSION["fakeNotes"] = array();
                            }

                            $fakeNote = array();
                            $fakeNote["note"] = $note;
                            $fakeNote["subject"] = $sub;
                            $fakeNote["semester"] = $semester;
                            $fakeNote["year"] = $year;
                            $fakeNote["fake"] = null;

                            array_push($_SESSION["fakeNotes"], json_decode(json_encode($fakeNote)));
                        }
                        else
                        {
                            $functionsNotes->addNote($note, $idUsers[0]->idUser,  $sub, $semester, $year);
                        }
                    }
                    else
                    {
                        $functionsNotes->addNote($note, $idUsers[0]->idUser,  $sub, $semester, $year);
                    }

                    // Redirect the user to the list of notes
                    $functions->redirect("notes");
                }
                else if ($note < 1.0 && $note > 6.0)
                {
                    echo ("erreur de saisie : la note n'est pas comprise entre 1 et 6");
                }
                else
                {
                    echo ("erreur de saisie : les notes doivent être arrondies à la demie");
                }
            }
            else if ($submit == "Annuler")
            {
                $functions->redirect("notes");
            }
        }
        require '../src/view/viewAddNote.php';
    }

    function AddGetParameter($param)
    {
        $key = explode("=", $param)[0];
        $value = explode("=", $param)[1];

        if (isset($_GET[$key]))
        {
            if ($_GET[$key] == $value)
            {
                return "";
            }
        }

        if ($_SERVER["PATH_INFO"] == $_SERVER["REQUEST_URI"])
        {
            return "?$param";
        }
        else
        {
            return "." . $_SERVER["REQUEST_URI"] . "&$param";
        }
    }
}