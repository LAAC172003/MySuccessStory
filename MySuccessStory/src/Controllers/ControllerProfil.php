<?php

namespace MySuccessStory\Controllers;

use MySuccessStory\Api\Model\Functions;

class ControllerProfil
{
    public function profil()
    {
        $functions = new Functions();
        if (!isset($_COOKIE['email'])) {
            header('Location:http://mysuccessstory/');
        }
        
        if ($functions->refreshCookie()) {
            $bearer = $_COOKIE['BearerCookie'];
            $emailParts = explode(".", $_COOKIE['email']);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://mysuccessstory/api/notes/$emailParts[0]/$emailParts[1]",
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
            var_dump($notes);
        }



        require '../src/view/viewProfil.php';
    }
}
