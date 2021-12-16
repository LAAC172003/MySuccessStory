<?php

namespace MySuccessStory\Controllers;

use MySuccessStory\Api\Model\Functions;

class ControllerAdd
{
    /**
     * Undocumented function
     *
     * @return void
     * @author Flavio <email@email.com>
     */
    public function addNote()
    {
        // Cookie
        $functions = new Functions();

        if ($functions->refreshCookie()) {
            // $emailParts = explode(".", $_COOKIE['email']);

            // Get subjects from the database
            $subjects = $functions->curl("http://mysuccessstory/api/subjects");

            function showSubjects($subjects)
            {
                
            }

            // Get periods from the database
            $period = $functions->curl("http://mysuccessstory/api/period");

            // var_dump($notes, $period, $subjects);
            
        }
        require '../src/view/viewAddNote.php';
    }
}
