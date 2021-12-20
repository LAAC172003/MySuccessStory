<?php

namespace MySuccessStory\Controllers;

use MySuccessStory\Api\Model\Functions;

class ControllerAdd
{
    /**
     * Add a note on the data base
     *
     * @return void
     * @author flavio.srsrd@eduge.ch
     */
    public function addNote()
    {
        // Cookie
        $functions = new Functions();

        if ($functions->refreshCookie()) {
            // $emailParts = explode(".", $_COOKIE['email']);

        }

        /**
        * Show subjects on the form html
        *
        * @return 
        * @author flavio.srsrd@eduge.ch
        */
        function showSubject()
        {
            // Cookie
            $functions = new Functions();

            // Get subjects from the database
            $subjects = $functions->curl("http://mysuccessstory/api/subjects");

        }

        /**
        * Show period on the form html
        *
        * @return 
        * @author flavio.srsrd@eduge.ch
        */
        function showPeriod()
        {
            // Cookie
            $functions = new Functions();

            // Get periods from the database
            $period = $functions->curl("http://mysuccessstory/api/period");
        }
        require '../src/view/viewAddNote.php';
    }
}
