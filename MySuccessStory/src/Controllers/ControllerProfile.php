<?php

namespace MySuccessStory\Controllers;

use MySuccessStory\Api\Model\Functions;
use MySuccessStory\Api\Model\Note;

class ControllerProfile
{
    /**
     * method who show the profile page
     *
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     *
     */
    public function profile()
    {
        // Cookie 
        $functions = new Functions();

        // Note object
        $functionsNotes = new Note();

        // If a user is logged
        if (!isset($_COOKIE['email']))
        {
            header('Location:http://mysuccessstory/');
        }

        if ($functions->refreshCookie())
        {
            $emailParts = explode(".", $_COOKIE['email']);

            $order = "note";
            if (isset($_GET["Order"]))
            {
                $order = $_GET["Order"];
            }

            $notes = $functions->curl("http://mysuccessstory/api/notes/$emailParts[0]/$emailParts[1]", $order);
        }
        require '../src/view/viewProfile.php';
    }
}
