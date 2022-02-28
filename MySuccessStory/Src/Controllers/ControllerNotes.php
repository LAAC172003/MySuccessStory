<?php

namespace MySuccessStory\Controllers;

use MySuccessStory\Api\Model\Functions;
use MySuccessStory\Api\Model\Note;

class ControllerNotes
{
    /**
     * method who show the the list of notes page
     *
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     * @author Jordan Folly <jordan.fllsd@eduge.ch>
     *
     */
    public function showNotes()
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

            $order = "idNote";
            if (isset($_GET["Order"]))
            {
                $order = $_GET["Order"];
            }

            $isASC = true;
            if (isset($_GET["isASC"]))
            {
                $isASC = $_GET["isASC"];
            }

            $notes = $functions->curl("http://mysuccessstory/api/getNotes/$emailParts[0]/$emailParts[1]/$order/$isASC");
        }

        require '../src/view/viewNotes.php';
    }
}
