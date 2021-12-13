<?php

namespace MySuccessStory\Controllers;

use MySuccessStory\Api\Model\Functions;

class ControllerProfil
{
    public function profil()
    {
        $functions = new Functions();
        // if (isset($_COOKIE['email'])) {
        // } else {

        // }

        if ($functions->refreshCookie()) {
            $bearer = $_COOKIE['BearerCookie'];
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://mysuccessstory/api/notes',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    "Bearer: $bearer",
                    'Authorization: Basic'
                ),
            ));
            $notes = json_decode(curl_exec($curl));
        }



        require '../src/view/viewProfil.php';
    }
}
