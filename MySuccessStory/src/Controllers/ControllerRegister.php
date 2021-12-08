<?php

namespace MySuccessStory\Controllers;

use MySuccessStory\api\model\Functions;
use MySuccessStory\api\model\User;

class ControllerRegister
{
    public function register()
    {
        $functions = new Functions();
        $users = new User();
        // require_once '../src/api/model/functions.php';
        @$pwd = $_POST["pwd"];
        @$pwd2 = $_POST["pwd2"];
        @$validate = $_POST["validate"];
        @$email = $_POST["email"];
        @$firstName = $_POST["firstName"];
        @$lastName = $_POST["lastName"];
        @$error = "";

        if (isset($validate)) {
            if (empty($firstName))  echo $erreur = "prenom laissé vide!";
            elseif (empty($lastName))  echo $erreur = "nom laissé vide!";
            elseif (empty($email)) echo $erreur = "Email laissé vide!";
            elseif (empty($pwd)) echo $erreur = "Mot de passe laissé vide!";
            elseif ($pwd != $pwd2) echo $erreur = "Mots de passe non identiques!";
            else {
                if ($functions->refreshCookie()) {
                    $curl = curl_init();
                    $bearer = $_COOKIE['BearerCookie'];
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "http://mysuccessstory/api/emailUsers/$email",
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
                    $compte = json_decode(curl_exec($curl));
                    var_dump($compte, $_COOKIE);

                    if ($compte != null) {
                        echo $error = "Login existe déjà!";
                    } else {
                        $users->CreateNewAccount($email, $pwd, $firstName, $lastName);
                        header("Location:http://mysuccessstory/login");
                    }
                }
            }
        }
        var_dump($_POST, $error);
        require '../src/view/viewRegister.php';
    }
}
