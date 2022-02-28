<?php

namespace MySuccessStory\Controllers;

use MySuccessStory\Api\Model\Functions;

class ControllerSubjects
{
    /**
     * CECI EST A SUPPRIMER A LA FIN DU SITE MODEL DE CLASSE RECUPERANT LES INFOS DE L'API
     *
     * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
     */
    public function subjects()
    {
        //collect the data in json

        $functions = new Functions();
        if ($functions->refreshCookie()) {
            $subjects = $functions->curl("http://mysuccessstory/api/subjects");
            //var_dump($subjects, $_COOKIE);
        }

        //error messages 
        if (isset($subjects->message)) {
            echo $subjects->message;
        } elseif ($subjects == null) {
            echo "Invalid token";
        } else {
            require '../src/view/viewSubjects.php';
        }
    }
}
