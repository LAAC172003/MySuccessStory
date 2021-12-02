<?php

namespace MySuccessStory\Controleur;

class ControleurSujets
{
    public function subjects()
    {
        require_once '../src/Api/Model/functions.php';
        if (refreshCookie()) {
            $curl = curl_init();
            $bearer = $_COOKIE['BearerCookie'];
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://mysuccessstoryapi/src/Api/controlleur/',
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
            $subjects = json_decode(curl_exec($curl));
        var_dump($subjects,$_COOKIE);
        }
        if (isset($subjects->message)) {
            echo $subjects->message;
        } elseif ($subjects == null) {
            echo "Invalid token";
        } else {
            require '../src/Vue/VueSujets.php';
        }
    }
}
