<?php

namespace MySuccessStory\Controllers;

use MySuccessStory\Api\Model\Functions;

class ControllerEdit
{
    public function editNote()
    {
        //if a user is logged
        if (!isset($_COOKIE['email'])) {
            header("Location:http://mysuccessstory/");
        }
        $functions = new Functions();
        // $functionsNotes = new Note();
        $emailParts = explode(".", $_COOKIE['email']);
        $semesters = [1, 2];

        if ($functions->refreshCookie()) {
            $subjects = $functions->curl("http://mysuccessstory/api/subjects");
            $years = $functions->curl("http://mysuccessstory/api/year");
            $idUsers = $functions->curl("http://mysuccessstory/api/userID/$emailParts[0]/$emailParts[1]");
            $notes = $functions->curl("http://mysuccessstory/api/notes/$emailParts[0]/$emailParts[1]");
        }
        // var_dump($years);
        var_dump($notes[0]);
        require '../src/view/viewEdit.php';
    }
}
