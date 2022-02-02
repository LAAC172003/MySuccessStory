<?php
namespace MySuccessStory\Controllers;

use MySuccessStory\Api\Model\Functions;
use MySuccessStory\Api\Model\Note;

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

            $submit = filter_input(INPUT_POST, 'validate', FILTER_SANITIZE_STRING);
            $note = filter_input(INPUT_POST, 'note', FILTER_VALIDATE_FLOAT);
            $idSubject = filter_input(INPUT_POST, 'subject', FILTER_VALIDATE_INT);
            $idYear = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
            $semester = filter_input(INPUT_POST, 'semester', FILTER_VALIDATE_INT);

            $idNote = $_GET['idNote'];

            if ($submit == "Valider") {
                $functionsNotes->update($note, $semester, $idSubject, $idNote, $idYear);
                header("Location:http://mysuccessstory/profile");
            }
        }
        var_dump($notes[0]);
        require '../src/view/viewEdit.php';
    }
}
