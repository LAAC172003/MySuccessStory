<?php

namespace MySuccessStory\Controllers;

use MySuccessStory\Api\Model\Functions;
use MySuccessStory\Api\Model\Note;
use MySuccessStory\Api\Model\Subject;

/**
 * method who delete a note
 *
 * @author Soares Rodrigues Flavio <flavio.srsrd@eduge.ch>
 */
class ControllerDelete
{
    /**
     * Delete a note on the data base
     *
     * @return void
     * @author Soares Rodrigues Flavio <flavio.srsrd@eduge.ch>
    */
    public function deleteNote()
    {
        // Cookie
        $functions = new Functions();

        // Note object
        $functionsNotes = new Note();

        // Subject object
        $functionsSubjects = new Subject();

        // Redirect to the home page if not logged
        $functions->redirectIfNotLogged();

        if ($functions->refreshCookie())
        {
            $idNote = $_GET['idNote'];
            $submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_STRING);

            // Get note by idNote in url
            $note = $functionsNotes->getNoteById($idNote);

            // Get subject by idNote in url
            $subject = $functionsSubjects->getSubjectByIdNote($idNote);

            // If the button delete is pressed : delete the data of the note in database
            if ($submit == "Delete")
            {
                // Delete the note in database
                $functionsNotes->deleteNoteById($idNote);

                // Redirect the user to the list of notes
                $functions->redirect("notes");
            }
            else if ($submit == "Cancel")
            {
                // Redirect the user to the list of notes
                $functions->redirect("notes");
            }
        }
        require '../src/view/viewDeleteNote.php';
    }
}