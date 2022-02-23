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
        // Cookie
        $functions = new Functions();

        // Note object
        $functionsNotes = new Note();

        // Split the email in 2
        $emailParts = explode(".", $_COOKIE['email']);

        // Redirect to the home page if not logged
        $functions->redirectIfNotLogged();

        if ($functions->refreshCookie()) {

            $idNote = $_GET['idNote'];

            // Get data from api
            $subjects = $functions->curl("http://mysuccessstory/api/subjects");
            $years = $functions->curl("http://mysuccessstory/api/year");
            $idUsers = $functions->curl("http://mysuccessstory/api/userID/$emailParts[0]/$emailParts[1]");
            $notes = $functions->curl("http://mysuccessstory/api/getNotes/$emailParts[0]/$emailParts[1]");

            $semesters = [1, 2];

            // Get all data of a note
            $noteById = $functionsNotes->getNoteById($idNote);

            $submit = filter_input(INPUT_POST, 'validate', FILTER_SANITIZE_STRING);
            $note = filter_input(INPUT_POST, 'note', FILTER_VALIDATE_FLOAT);
            $idSubject = filter_input(INPUT_POST, 'subject', FILTER_VALIDATE_INT);
            $idYear = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
            $semester = filter_input(INPUT_POST, 'semester', FILTER_VALIDATE_INT);

            // If the button submit is pressed
            if ($submit == "Valider")
            {
                // If $note value is between 1 and 6 including floating numbers by 0.5
                if ($note >= 1.0 && $note <= 6.0 && fmod($note, 0.5) == 0)
                {
                    // Update the value on the database
                    $functionsNotes->update($note, $semester, $idSubject, $idNote, $idYear);

                    // Redirect the user to the list of notes
                    $functions->redirect("notes");
                }
            }
        }
        require '../src/view/viewEdit.php';
    }
}
